<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EloquentJs - CRUD - ordered Vuetify data table example</title>
  <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet" type="text/css">
  <link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="app">
  <v-app id="inspire">


    <v-toolbar
      flat
      color="white"
    >
      <v-toolbar-title>My CRUD</v-toolbar-title>
      <v-divider
        class="mx-2"
        inset
        vertical
      ></v-divider>
      <v-spacer></v-spacer>
      <v-dialog
        v-model="dialog"
        max-width="500px"
        
      >
        <v-btn
          slot="activator"
          color="primary"
          dark
          class="mb-2"
        >
          New Item
        </v-btn>

        <v-card>
          <v-card-title>
            <span class="headlines">
              @{{ formTitle }}
            </span>
          </v-card-title>

          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex
                  xs12
                  sm6
                  md4
                >
                  <v-text-field
                    v-model="editedItem.name"
                    label="User name"
                  ></v-text-field>
                </v-flex>
                <v-flex
                  xs12
                  sm6
                  md4
                >
                  <v-text-field
                    v-model="editedItem.email"
                    label="E-mail"
                  ></v-text-field>
                </v-flex>

                <v-flex
                  xs12
                  sm6
                  md4
                >
                  <v-text-field
                    v-model="editedItem.password"
                    label="password"
                  ></v-text-field>
                </v-flex>
       
 
              </v-layout>
            </v-container>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="blue darken-1"
              flat
              @click="close"
            >
              Cancel
            </v-btn>
            <v-btn
              color="blue darken-1"
              flat
              @click="save"
            >
              Save
            </v-btn>
          </v-card-actions>
        </v-card>
        
      </v-dialog>
    </v-toolbar>




  <v-data-table
      :headers="headers"
      :items="users"
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

        <td class="justify-center layout px-0">
          <v-icon
            small
            class="mr-2"
            @click="editItem(props.item)"
          >
            edit
          </v-icon>
          <v-icon
            small
            @click="deleteItem(props.item)"
          >
            delete
          </v-icon>
        </td>
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
            dialog: false,
            editedIndex: -1,
            editedItem: {
                name: '',
                email: '',
                password: ''
            },
            defaultItem: {
                name: '',
                email: '',
                password: ''
            },

            totalUsers: 0,
            users: [],
            loading: true,
            pagination: {
                rowsPerPage:10,
                descending: true
            },
            headers: [
                {
                text: 'ID',
                align: 'left',
                sortable: true,
                value: 'id'
                },
                { text: 'Name', value: 'name', sortable: true },
                { text: 'E-mail', value: 'email', sortable: true },
                { text: 'Created at', value: 'created_at', sortable: false },
                { text: 'Updated at', value: 'updated_at', sortable: false },

                { text: 'Actions', value: 'name', sortable: false }
            ]
        }
    },

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'New Item!' : 'Edit Item'
      }
    },

    mounted () {
        UserModel.orderBy(this.pagination.sortBy, !this.pagination.descending)
            .paginate(this.pagination.rowsPerPage ,1)
            .then(response => {
                this.users = response.data
                this.totalUsers = response.total
                this.loading = false
            })
    },

    watch: {
        pagination: {
            handler (newState, oldState) {
                if (typeof oldState.totalItems == 'undefined') return;
                this.loading = true;
                if (newState.rowsPerPage == -1) // All values
                {
                    var rowsPerPage = this.totalUsers;
                    var page = 1;
                }
                else
                {
                    var rowsPerPage = newState.rowsPerPage;
                    var page = newState.page
                }
                UserModel.orderBy(newState.sortBy, !newState.descending).paginate(rowsPerPage, page)
                    .then(response => {
                        this.users = response.data
                        this.totalUsers = response.total
                        this.loading = false
                    })
            },
            deep: false
        }
    },

    methods: {

        editItem (item) {
          this.editedIndex = this.users.indexOf(item)
          this.editedItem = Object.assign({}, item)
          this.dialog = true
        },

        deleteItem (item) {
          const index = this.users.indexOf(item)
          if (confirm('Are you sure you want to delete this item?'))
          {
             UserModel.destroy(item.id).then(data => {
               if (data === 1)
               {
                this.users.splice(index, 1)
               }
             });
          }
        },


        close () {
            this.dialog = false
            setTimeout(() => {
            this.editedItem = Object.assign({}, this.defaultItem)
            this.editedIndex = -1
            }, 300)
        },

        save () {
            if (this.editedIndex === -1)
            {
                UserModel.create(this.editedItem).then(data => {
                  this.close();
                  var o = Object.assign({}, this.pagination)
                  o.page = 1;
                  this.pagination = o;
                });
            }
            else
            {
              UserModel.where('id', this.editedItem.id).update(this.editedItem).then(data => {
                if (data === 1)
                {
                  Object.assign(this.users[this.editedIndex], this.editedItem);
                  this.close();
                }
                  
                  
              });
            }

            /*
            if (this.editedIndex > -1) {
            Object.assign(this.desserts[this.editedIndex], this.editedItem)
            } else {
            this.desserts.push(this.editedItem)
            }
            this.close()
            */
        }
    }


})
  </script>
</body>
</html>
