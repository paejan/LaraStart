<template>
    <div class="users">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Users</h3>
                        <div class="tableFilters">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" v-model="tableData.search" class="form-control float-right" placeholder="Search Users" @input="getUsers()">
                                <div class="control">
                                    <div class="select">
                                        <select v-model="tableData.length" @change="getUsers()">
                                            <option v-for="(records, index) in perPage" :key="index" :value="records">{{records}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="card-body table-responsive p-0">   
                        <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
                            <tbody>
                                <tr v-for="user in users" :key="user.id">
                                    <td>{{user.id}}</td>
                                    <td>{{user.name}}</td>
                                    <td>{{user.email}}</td>
                                </tr>
                            </tbody>
                        </datatable>
                        <pagination :pagination="pagination"
                                    @prev="getUsers(pagination.prevPageUrl)"
                                    @next="getUsers(pagination.nextPageUrl)">
                        </pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Datatable from "./DataTable.vue";
import Pagination from "./Pagination.vue";
export default {
  components: { datatable: Datatable, pagination: Pagination },
  created() {
    this.getUsers();
  },
  data() {
    let sortOrders = {};
    let columns = [
      { width: "33%", label: "ID", name: "id" },
      { width: "33%", label: "Name", name: "name" },
      { width: "33%", label: "E-mail", name: "email" }
    ];
    columns.forEach(column => {
      sortOrders[column.name] = -1;
    });
    return {
      users: [],
      columns: columns,
      sortKey: "deadline",
      sortOrders: sortOrders,
      perPage: ["10", "20", "30"],
      tableData: {
        draw: 0,
        length: 10,
        search: "",
        column: 0,
        dir: "asc"
      },
      pagination: {
        lastPage: "",
        currentPage: "",
        total: "",
        lastPageUrl: "",
        nextPageUrl: "",
        prevPageUrl: "",
        from: "",
        to: ""
      }
    };
  },
  methods: {
    getUsers(url = "api/users") {
      this.tableData.draw++;
      axios
        .get(url, { params: this.tableData })
        .then(response => {
          let data = response.data;
          if (this.tableData.draw == data.draw) {
            this.users = data.data.data;
            this.configPagination(data.data);
          }
        })
        .catch(errors => {
          console.log(errors);
        });
    },
    configPagination(data) {
      this.pagination.lastPage = data.last_page;
      this.pagination.currentPage = data.current_page;
      this.pagination.total = data.total;
      this.pagination.lastPageUrl = data.last_page_url;
      this.pagination.nextPageUrl = data.next_page_url;
      this.pagination.prevPageUrl = data.prev_page_url;
      this.pagination.from = data.from;
      this.pagination.to = data.to;
    },
    sortBy(key) {
      this.sortKey = key;
      this.sortOrders[key] = this.sortOrders[key] * -1;
      this.tableData.column = this.getIndex(this.columns, "name", key);
      this.tableData.dir = this.sortOrders[key] === 1 ? "asc" : "desc";
      this.getUsers();
    },
    getIndex(array, key, value) {
      return array.findIndex(i => i[key] == value);
    }
  }
};
</script>