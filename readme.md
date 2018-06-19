# EloquentJs

## Install:
```
git clone https://github.com/ognjen-petrovic/EloquentJs.git
cd EloquentJs
composer install

cp .env.example .env
php artisan key:generate

// edit .env and setup the database, then seed...
php artisan migrate
php artisan db:seed

// start server
cd public
php -S localhost:80

// available demo pages:
// http://localhost/codemirror
// http://localhost/vuetify/data-table
// http://localhost/vuetify/data-table-order-by
``` 

## Online demo pages:

[Codemirror console](http://eloquentjs.ognjen-petrovic.from.hr/codemirror)

[Vuetify data table example](http://eloquentjs.ognjen-petrovic.from.hr/vuetify/data-table)

[Vuetify data table example, orderable by ID, Name or E-mail](http://eloquentjs.ognjen-petrovic.from.hr/vuetify/data-table-order-by)

## Example:
```javascript
var UserModel = EloquentJs.ModelFactory('User');
UserModel.all().then(data => console.log('All users: ', data));
UserModel.find(10).then(data => console.log('User with id=10: ', data));
UserModel.where('id',  10).get().then(data => console.log('User with id=10: ', data));
UserModel.where('name', 'like', 'Dr.%').get().then(data => console.log('Doctors: ', data));
UserModel.where('name', 'like', 'Dr.%').orWhere('name', 'like', 'Prof.%').get().then(data => console.log('Drs and Profs: ', data));

var ObjectModel = EloquentJs.ModelFactory('Object');
ObjectModel.all().then(data => console.log('All objects: ', data));
UserModel.where('id',  '<', 5).with('objects').get().then(data => console.log('Some users with related objects: ', data));

//update
ObjectModel.where('id', '<', 5).update({capacity: 1});

//paginate
UserModel.paginate(10,1).then(data => console.log('Paginate users: ', data));
UserModel.orderBy('name').paginate(10,1).then(data => console.log('Paginate ordered users: ', data));
```
