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

    ejs.ModelFactory = function (modelName) {
        var modelClass = class {
            constructor(data) {
                for (var prop in data) {
                    this[prop] = data[prop];
                }
            }
        };
    
        modelClass._model_name = modelName;
        modelClass._methods = [];
    
        modelClass._addMethod = function (name, params) {
            if (this._methods.length == 0) {
                name = this._model_name + '::' + name;
            }
            this._methods.push(this._createMethodObject(name, params));
        }
    
    
        modelClass._createMethodObject = function (name, params) {
            params = (params) ? params : [];
            return { 'method': name, 'params': params };
        }
    
    
    
        modelClass._getAndClearMethods = function () {
            return this._methods.splice(0, this._methods.length);
        }
    
        modelClass.all = function () {
            return new Promise((resolve, reject) => {
                this._addMethod('all', []);
    
                var promise = ejs.httpAdapter.get(this._getAndClearMethods());
                promise.then(data => {
                    var models = [];
                    for (var i = 0; i < data.length; ++i)
                        models.push(new modelClass(data[i]))
                    resolve(models)
                });
            });
        }
    
        modelClass.find = function (id) {
    
            return new Promise((resolve, reject) => {
                this._addMethod('find', [id]);
    
                var promise = ejs.httpAdapter.get(this._getAndClearMethods());
                promise.then(data => {
    
                    if (data == null)
                        resolve(null);
                    else
                        resolve(new modelClass(data));
                });
            });
    
    
            var promise = ejs.httpAdapter.get(this._getAndClearMethods());
            return promise;
        }
    
        modelClass.where = function (column, operator, value) {
            if (value == undefined) {
                value = operator;
                operator = '=';
            }
    
            this._addMethod('where', [column, operator, value]);
            return this;
        }
    
        modelClass.orWhere = function (column, operator, value) {
            if (value == undefined) {
                value = operator;
                operator = '=';
            }
    
            this._addMethod('orWhere', [column, operator, value]);
            return this;
        }
    
        modelClass.get = function (columns) {
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
    
    
        return modelClass;
    }
})('EloquentJs');


