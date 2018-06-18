<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome to Vuetify</title>
  <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet" type="text/css">
  <link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet" type="text/css"></link>
</head>
<body>
<div id="app">
  <v-app id="inspire">
  <v-data-table
      :headers="headers"
      :items="users"
      :search="search"
      :pagination.sync="pagination"
      :total-items="totalUsers"
      :loading="loading"
      class="elevation-1"
    >
      <template slot="items" slot-scope="props">
        <td>@{{ props.item.id }}</td>
        <td class="text-xs-left">@{{ props.item.name }}</td>
        <td class="text-xs-left">@{{ props.item.email }}</td>
        <td class="text-xs-left">@{{ props.item.created_at }}</td>
        <td class="text-xs-left">@{{ props.item.updated_at }}</td>
      </template>
    </v-data-table>
  </v-app>
</div>

  <script src="{{URL::asset('js/eloquent.js')}}"></script>
  <script src="https://unpkg.com/vue/dist/vue.js"></script>
  <script src="https://unpkg.com/vuetify/dist/vuetify.js"></script>
  <script>
var UserModel = EloquentJs.ModelFactory('User');
new Vue({
    el: '#app',
    data () {
        return {
            search: '',
            totalUsers: 0,
            users: [],
            loading: true,
            pagination: {
                rowsPerPage:10,
            },
            headers: [
                {
                text: 'ID',
                align: 'left',
                sortable: false,
                value: 'id'
                },
                { text: 'Name', value: 'name', sortable: false },
                { text: 'E-mail', value: 'email', sortable: false },
                { text: 'Created at', value: 'created_at', sortable: false },
                { text: 'Updated at', value: 'updated_at', sortable: false },
            ]
        }
    },

    mounted () {
        UserModel.paginate(this.pagination.rowsPerPage ,1)
            .then(response => {
                this.users = response.data
                this.totalUsers = response.total
                this.loading = false
            })
    },

    watch: {
        pagination: {
        handler (e, e0) {
            if (e0.totalItems == 0 || typeof e0.totalItems == 'undefined') return;
            if (e.rowsPerPage == -1) // All values
            {
                var rowsPerPage = e.totalItems;
                var page = 1;
            }
            else
            {
                var rowsPerPage = e.rowsPerPage;
                var page = e.page
            }
            UserModel.paginate(rowsPerPage, page)
                .then(response => {
                    this.users = response.data
                    this.totalUsers = response.total
                    this.loading = false
                })
        },
        deep: false
        }
    },


})
  </script>
</body>
</html>
