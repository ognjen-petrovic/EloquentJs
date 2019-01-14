<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EloquentJs - Vuetify data table example</title>
  <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet" type="text/css">
  <link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="app">
  <v-app id="inspire">
  <v-data-table
      :headers="headers"
      :items="records"
      :pagination.sync="pagination"
      :total-items="totalRecords"
      :loading="loading"
      class="elevation-1"
    >
      <template slot="items" slot-scope="props">
        @foreach ($headers as $header)
            <td>@{{ props.item.{!! $header['value'] !!} }}</td>
        @endforeach
      </template>
    </v-data-table>
  </v-app>
</div>

  <script src="{{URL::asset('js/eloquent.js')}}"></script>
  <script src="https://unpkg.com/vue/dist/vue.js"></script>
  <script src="https://unpkg.com/vuetify/dist/vuetify.js"></script>
  <script>
var Model = EloquentJs.ModelFactory('{{ $model }}');
new Vue({
    el: '#app',
    data () {
        return {
            totalRecords: 0,
            records: [],
            loading: true,
            pagination: {
                rowsPerPage:{{ $rowsPerPage }},
            },
            headers: @json($headers)
        }
    },

    mounted () {
        Model.paginate(this.pagination.rowsPerPage ,1)
            .then(response => {
                this.records = response.data
                this.totalRecords = response.total
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
                    var rowsPerPage = this.totalRecords;
                    var page = 1;
                }
                else
                {
                    var rowsPerPage = newState.rowsPerPage;
                    var page = newState.page
                }
                Model.paginate(rowsPerPage, page)
                    .then(response => {
                        this.records = response.data
                        this.totalRecords = response.total
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
