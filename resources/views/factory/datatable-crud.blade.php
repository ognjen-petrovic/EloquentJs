<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EloquentJs - CRUD - ordered Vuetify data table example</title>
  <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet" type="text/css">
  <link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet" type="text/css">
  <script src="{{URL::asset('js/eloquent.js')}}"></script>
  <script src="https://unpkg.com/vue/dist/vue.js"></script>
  <script src="https://unpkg.com/vuetify/dist/vuetify.js"></script>
</head>
<body>
<div id="app">
  <v-app id="inspire">


    <v-toolbar
      flat
      color="white"
    >
        <v-toolbar-title>{{ str_plural($model) }}</v-toolbar-title>
        <v-divider
            class="mx-2"
            inset
            vertical
        ></v-divider>
            <v-spacer></v-spacer>

        <v-dialog v-model="dialog" max-width="500px">

            <v-btn slot="activator" color="primary" dark class="mb-2">
                New {{ $model }}
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
                            <v-flex xs12 sm6 md4>
                                <v-text-field v-model="editedItem.name" label="User name"></v-text-field>
                            </v-flex>

                            <v-flex xs12 sm6 md4>
                                <v-text-field v-model="editedItem.email" label="E-mail"></v-text-field>
                            </v-flex>

                            <v-flex xs12 sm6 md4>
                                <v-text-field v-model="editedItem.password" label="password"></v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="close">Cancel</v-btn>
                    <v-btn color="blue darken-1" flat @click="save">Save</v-btn>
                </v-card-actions>

            </v-card>
        
      </v-dialog>
    </v-toolbar>

  <v-data-table
      :headers="headers"
      :items="items"
      :pagination.sync="pagination"
      :total-items="totalItems"
      :loading="loading"
      class="elevation-1"
    >
      <template slot="items" slot-scope="props">

        @foreach ($headers as $header)
            <td>@{{ props.item.{!! $header['value'] !!} }}</td>
        @endforeach

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


  <script>
// var Model = {
//     model: 
// }
var Model = function()
{
    @if (count($with) > 0)
        return Model.Model.with(@json($with));
    @else
        return Model.Model
    @endif 
}
Model.Model = EloquentJs.ModelFactory('{{ $model }}');

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

            totalItems: 0,
            items: [],
            loading: true,
            pagination: {
                rowsPerPage:10,
                descending: true
            },
            headers: @json($headers)
        }
    },

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'New {{ $model }}' : 'Edit {{ $model }}'
      }
    },

    mounted () {
        Model().orderBy(this.pagination.sortBy, !this.pagination.descending)
            .paginate(this.pagination.rowsPerPage ,1)
            .then(response => {
                this.items = response.data
                this.totalItems = response.total
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
                    var rowsPerPage = this.totalItems;
                    var page = 1;
                }
                else
                {
                    var rowsPerPage = newState.rowsPerPage;
                    var page = newState.page
                }
                Model().orderBy(newState.sortBy, !newState.descending).paginate(rowsPerPage, page)
                    .then(response => {
                        this.items = response.data
                        this.totalItems = response.total
                        this.loading = false
                    })
            },
            deep: false
        }
    },

    methods: {

        editItem (item) {
          this.editedIndex = this.items.indexOf(item)
          this.editedItem = Object.assign({}, item)
          this.dialog = true
        },

        deleteItem (item) {
          const index = this.items.indexOf(item)
          if (confirm('Are you sure you want to delete this item?'))
          {
             Model.destroy(item.id).then(data => {
               if (data === 1)
               {
                this.items.splice(index, 1)
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
                Model.create(this.editedItem).then(data => {
                  this.close();
                  var o = Object.assign({}, this.pagination)
                  o.page = 1;
                  this.pagination = o;
                });
            }
            else
            {
              Model.where('id', this.editedItem.id).update(this.editedItem).then(data => {
                if (data === 1)
                {
                  Object.assign(this.items[this.editedIndex], this.editedItem);
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
