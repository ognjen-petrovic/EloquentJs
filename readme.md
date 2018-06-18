# EloquentJs
```
php artisan migrate
php artisan db:seed
``` 

[Vutify data table example](http://eloquentjs.ognjen-petrovic.from.hr/vuetify/data-table)

## Example
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
UserModel.orderBy('name').paginate(10,1).then(data => console.log('Paginate ordered users: ', data));;
```
