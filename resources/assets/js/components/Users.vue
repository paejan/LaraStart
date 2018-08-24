<template>
    <div class="users">
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fa fa-users"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Users <i @click="getUserCount()" class="fa fa-sync"></i> </span>
                <span class="info-box-number" v-if="loadingUserCount">
                    <i class="fa fa-spinner fa-spin"></i>
                </span>
                <span class="info-box-number" v-else>
                    {{ userCount.toLocaleString('en') }}
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fa fa-user-plus"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">New Users</span>
                <span class="info-box-number">
                   {{ newUserCount.toLocaleString('en') }}
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-primary elevation-1"><i class="fa fa-user-check"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Active Users</span>
                <span class="info-box-number">
                    {{ (activeUserCount = 0 ? ((activeUserCount / userCount) * 100).toFixed() : 0) }}
                    <small>%</small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fa fa-user-check"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Currently Online</span>
                <span class="info-box-number">
                    {{ usersOnlineCount }}
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Users
                            <button type="button" data-toggle="tooltip" data-placement="bottom" title="New User" class="btn btn-outline-success btn-sm">
                                <i class="fa fa-user-plus"></i>
                            </button>
                            <button v-if="loadingUsers" type="button" data-toggle="tooltip" data-placement="bottom" title="Refresh" class="btn btn-outline-primary btn-sm">
                                <i class="fa fa-sync fa-spin"></i>
                            </button>
                            <button v-else @click="getUsers()" type="button" data-toggle="tooltip" data-placement="bottom" title="Refresh" class="btn btn-outline-primary btn-sm">
                                <i class="fa fa-sync"></i>
                            </button>
                        </h3>
                        <div class="card-tools">
                            <div class="input-group input-group-sm">
                                <input type="text" name="table_search" v-model="tableData.search" class="form-control float-right" placeholder="Search Users" @input="getUsers()">
                            </div>
                        </div>
                    </div>
                     <div class="card-body table-responsive p-0">   
                        <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
                            <tbody>
                                <tr v-for="user in users" :key="user.id">
                                    <td>{{user.name}}</td>
                                    <td>{{user.email}}</td>
                                    <td>
                                        <button type="button" class="btn btn-outline-primary btn-sm"><i class="fa fa-user-edit"></i></button>
                                        <button @click="getUser(user.id); showModal = true" type="button" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash-alt"></i></button>
                                    </td>
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
        <!-- Delete User Modal -->
        <div v-if="showModal">
            <transition name="modal">
                <div class="modal-mask">
                    <div class="modal-wrapper">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteUserModal">Deleting: {{ user.name }}</h5>
                                        <button type="button" class="close" @click="showModal = false" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete {{ user.name }}?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" @click="showModal = false">Close</button>
                                    <button v-if="loadingDeleteUser" disabled type="button" class="btn btn-danger"><i class="fa fa-sync fa-spin"></i></button>
                                    <button v-else @click="deleteUser(user.id)" type="button" class="btn btn-danger">Delete User</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
import Datatable from "./DataTable.vue";
import Pagination from "./Pagination.vue";
export default {
  components: {
    datatable: Datatable,
    pagination: Pagination
  },

  created() {
    this.getUsers();
    this.getUserCount();
    this.getNewUserCount();
    this.getActiveUserCount();
    this.getUsersOnlineCount();
  },

  data() {
    let sortOrders = {};
    let columns = [
      { width: "33%", label: "Name", name: "name" },
      { width: "33%", label: "E-mail", name: "email" },
      { width: "33%", label: "Actions", name: "actions" }
    ];
    columns.forEach(column => {
      sortOrders[column.name] = -1;
    });
    return {
      showModal: false,
      user: [],
      users: [],
      loadingDeleteUser: false,
      loadingUsers: true,
      userCount: 0,
      loadingUserCount: true,
      newUserCount: 0,
      activeUserCount: 0,
      usersOnlineCount: 0,
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
      this.loadingUsers = true;
      this.tableData.draw++;
      axios
        .get(url, { params: this.tableData })
        .then(response => {
          let data = response.data;
          if (this.tableData.draw == data.draw) {
            this.users = data.data.data;
            this.configPagination(data.data);
            this.loadingUsers = false;
          }
        })
        .catch(errors => {
          console.log(errors);
        });
    },
    refresh() {
      this.getUsers();
      this.getUserCount();
      this.getNewUserCount();
      this.getActiveUserCount();
      this.getUsersOnlineCount();
    },
    getUserCount() {
      this.loadingUserCount = true;
      axios.get("api/count/users").then(({ data }) => {
        this.userCount = data;
        this.loadingUserCount = false;
      });
    },
    getUser(id) {
      axios.get("api/user/" + id).then(({ data }) => {
        this.user = data;
      });
    },
    getNewUserCount() {
      axios.get("api/count/users/new").then(({ data }) => {
        this.newUserCount = data;
      });
    },
    getActiveUserCount() {
      axios.get("api/count/users/active").then(({ data }) => {
        this.activeUserCount = data;
      });
    },
    getUsersOnlineCount() {
      axios.get("api/count/users/online").then(({ data }) => {
        this.usersOnlineCount = data;
      });
    },
    deleteUser(id) {
      this.loadingDeleteUser = true;
      axios.get("api/user/delete/" + id).then(({ data }) => {
        //
      });
      this.loadingDeleteUser = false;
      this.refresh();
      this.showModal = false;
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
      this.loadingUsers = false;
    },
    getIndex(array, key, value) {
      return array.findIndex(i => i[key] == value);
    }
  }
};
</script>