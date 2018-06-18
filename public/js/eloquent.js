;(function(EloquentJs) {

    var ejs = window[EloquentJs] = window[EloquentJs] || {};

    ejs.endpoint = '/api/v1';

    ejs.http = function (url) {

        // A small example of object
        var core = {

            // Method that performs the ajax request
            ajax: function(method, url, args, responseType) {

                // Creating a promise
                var promise = new Promise(function(resolve, reject) {

                    // Instantiates the XMLHttpRequest
                    var client = new XMLHttpRequest();
                    client.responseType = (responseType ) ? responseType : 'json';
                    var uri = url;

                    if (args /*&& (method === 'POST' || method === 'PUT')*/) {
                        uri += '?';
                        var argcount = 0;
                        for (var key in args) {
                            if (args.hasOwnProperty(key)) {
                                if (argcount++) {
                                    uri += '&';
                                }
                                uri += encodeURIComponent(key) + '=' + encodeURIComponent(args[key]);
                            }
                        }
                    }

                    client.open(method, uri);
                    client.send();

                    client.onload = function() {
                        if (this.status >= 200 && this.status < 300) {
                            // Performs the function "resolve" when this.status is equal to 2xx
                            resolve(this.response);
                        } else {
                            // Performs the function "reject" when this.status is different than 2xx
                            reject(this.statusText);
                        }
                    };
                    client.onerror = function() {
                        reject(this.statusText);
                    };
                });

                // Return the promise
                return promise;
            }
        };

        // Adapter pattern
        return {
            'get': function(args) {
                return core.ajax('GET', url, args);
            },
            'post': function(args) {
                return core.ajax('POST', url, args);
            },
            'put': function(args) {
                return core.ajax('PUT', url, args);
            },
            'delete': function(args) {
                return core.ajax('DELETE', url, args);
            }
        };
    };

    ejs.httpAdapter = 
    {
    	'get': function(payload)
    	{
            return ejs.http(ejs.endpoint).get({'payload' : JSON.stringify(payload)});
   		}
    }
})('EloquentJs');

;(function(EloquentJs) {

    var ejs = window[EloquentJs] = window[EloquentJs] || {};

    ejs.common = {};

    ejs.common._addMethod = function (name, params) {
        if (this._methods.length == 0) {
            name = this._model_name + '::' + name;
        }
        this._methods.push(this._createMethodObject(name, params));
    }

    ejs.common._createMethodObject = function (name, params) {
        params = (params) ? params : [];
        return { 'method': name, 'params': params };
    }

    ejs.common._getAndClearMethods = function () {
        return this._methods.splice(0, this._methods.length);
    }

    ejs.common.all = function () {
        return new Promise((resolve, reject) => {
            this._addMethod('all', []);

            var promise = ejs.httpAdapter.get(this._getAndClearMethods());
            promise.then(data => {
                resolve(data); 
                /*
                var models = [];
                for (var i = 0; i < data.length; ++i)
                    models.push(new this(data[i]))
                resolve(models)
                */
            });
        });
    }

    ejs.common.count = function () {
        this._addMethod('count', []);
        return ejs.httpAdapter.get(this._getAndClearMethods());
    }

    //https://laravel.com/api/5.5/Illuminate/Database/Query/Builder.html#method_paginate
    //int $perPage = 15, array $columns = ['*'], string $pageName = 'page', int|null $page = null
    ejs.common.paginate = function (perPage, page) {
        this._addMethod('paginate', [perPage, ['*'], 'page', page]);
        return ejs.httpAdapter.get(this._getAndClearMethods());
    }

    ejs.common.find = function (id) {
    
        return new Promise((resolve, reject) => {
            this._addMethod('find', [id]);

            var promise = ejs.httpAdapter.get(this._getAndClearMethods());
            promise.then(data => {

                if (data == null)
                    resolve(null);
                else
                    resolve(new this(data));
            });
        });


        var promise = ejs.httpAdapter.get(this._getAndClearMethods());
        return promise;
    }

    ejs.common.where = function (column, operator, value) {
        if (value == undefined) {
            value = operator;
            operator = '=';
        }

        this._addMethod('where', [column, operator, value]);
        return this;
    }

    ejs.common.orWhere = function (column, operator, value) {
        if (value == undefined) {
            value = operator;
            operator = '=';
        }

        this._addMethod('orWhere', [column, operator, value]);
        return this;
    }

    ejs.common.get = function (columns) {
        return new Promise((resolve, reject) => {
            this._addMethod('get', [columns]);

            var promise = ejs.httpAdapter.get(this._getAndClearMethods());
            promise.then(data => {
                resolve(data);
                /*
                var models = [];
                for (var i = 0; i < data.length; ++i)
                    models.push(new modelClass(data[i]))
                resolve(models)
                */
            });
        });
    }

    ejs.common.with = function (relation_name) {
        this._addMethod('with', [relation_name]);
        return this;
    }

    /**
     * oFieldsValues {address: 'new addres, age: 666, ....}
     */
    ejs.common.update = function (oFieldsValues) {
        this._addMethod('update', [oFieldsValues]);
        var promise = ejs.httpAdapter.get(this._getAndClearMethods());
        return promise;
    }


    ejs.ModelFactory = function (modelName) {
        var modelClass = class {
            constructor(data) {
                for (var prop in data) {
                    this[prop] = data[prop];
                }
            }

            save(){
                return new Promise((resolve, reject) => {
                    /*                  
                    modelClass._addMethod('find', [this.id]);
                    modelClass._addMethod('fill', [this]);
                    modelClass._addMethod('save', []); 
                    */
                    modelClass._addMethod('updateOrCreate', [this]);
                    var promise = ejs.httpAdapter.get(modelClass._getAndClearMethods());
                    promise.then(data => {
                        resolve(data);
                    });
                });
            }
        };
    
        modelClass._model_name = modelName;
        modelClass._methods = [];
    
        modelClass._addMethod = ejs.common._addMethod;
        modelClass._createMethodObject = ejs.common._createMethodObject;
        modelClass._getAndClearMethods = ejs.common._getAndClearMethods;
    
        modelClass.all = ejs.common.all;
        modelClass.count = ejs.common.count;
        modelClass.paginate = ejs.common.paginate;
        modelClass.find = ejs.common.find;
        modelClass.where = ejs.common.where;
        modelClass.orWhere = ejs.common.orWhere;
        modelClass.get = ejs.common.get;
        modelClass.with = ejs.common.with;
        modelClass.update = ejs.common.update
    
        return modelClass;
    }
})('EloquentJs');


