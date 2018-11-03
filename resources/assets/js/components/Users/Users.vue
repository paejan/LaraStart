<template>
    <div class="users container">
        <notifications group="users" position="bottom right" :speed="2000"/>
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <user-count></user-count>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <new-users></new-users>
            </div>
            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>
            <div class="col-12 col-sm-6 col-md-3">
                <active-users></active-users>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <online-users></online-users>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-sm-6">
                <h3 class="m-0 text-dark">
                    Users
                </h3>
            </div>
        </div>
    
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title col-3">
                            <div class="input-group">
                                <input type="text" class="form-control" v-model="tableData.search" @input="getUsers()" placeholder="Search Users .." aria-label="Search Users" aria-describedby="userSearch">
                                <div class="input-group-prepend">
                                    <div class="input-group-text" id="userSearch"><i class="fa fa-search"></i></div>
                                </div>
                                <div class="input-group-prepend">
                                    <div v-if="loadingUsers" class="input-group-text" id="userSearch"><i class="fa fa-sync fa-spin"></i></div>
                                    <div v-else @click="getUsers()" class="input-group-text" id="userSearch"><i class="fa fa-sync"></i></div>
                                </div>
                            </div>
                        </h3>
                        <div class="card-tools">
                            <router-link :to="{ name : 'new_user' }">
                                <button type="button" class="btn btn-primary btn-lg"> <i class="fa fa-user-plus"></i> New User </button>
                            </router-link>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">   
                        <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
                             <tbody v-if="loadingTable">
                                <tr>
                                    <td>Loading ... </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr v-for="user in users" :key="user.id">
                                    <td><img :src="user.profile_photo" class="img-circle" style="height: 4rem; width: 4rem; margin-right: 10px;"> {{user.name}}</td>
                                    <td>{{user.email}}</td>
                                    <td v-if="user.roles[0]">{{user.roles[0].name}}</td>
                                    <td v-else>None (User Disabled)</td>
                                    <td>
                                        <router-link :to="{ name : 'edit_user', params : { id : user.id } }">
                                            <button type="button" class="btn btn-outline-primary btn-sm"><i class="fa fa-user-edit"></i> Edit User</button>
                                        </router-link>
                                        <button type="button" class="btn btn-outline-success btn-sm"><i class="fa fa-unlock"></i> Reset Password</button>
                                        <button @click="getUser(user.id); showDeleteUserModal = true" type="button" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash-alt"></i> Delete User</button>
                                    </td>
                                </tr>
                            </tbody>
                        </datatable>
                    </div>
                    <div class="card-footer">
                        <pagination :pagination="pagination"
                                    @prev="getUsers(pagination.prevPageUrl)"
                                    @next="getUsers(pagination.nextPageUrl)">
                        </pagination>
                    </div>
                </div>
            </div>
        </div>
        <!-- Delete User Modal -->
        <modal v-if="showDeleteUserModal">
            <template slot="modal-title">Deleting: {{ user.name }}</template>
            <template slot="modal-close">
                <button type="button" class="close" @click="showDeleteUserModal = false" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </template>
            <template slot="modal-body">Are you sure you want to delete {{ user.name }}?</template>
            <template slot="modal-footer">
                <button type="button" class="btn btn-secondary" @click="showDeleteUserModal = false">Close</button>
                <button v-if="loadingDeleteUser" disabled type="button" class="btn btn-danger"><i class="fa fa-sync fa-spin"></i></button>
                <button v-else @click="deleteUser(user)" type="button" class="btn btn-danger">Delete User</button>
            </template>
        </modal>
        <!-- /.Delete User Modal -->
    </div>
</template>

<script>
import Datatable from "../DataTable.vue";
import Pagination from "../Pagination.vue";
import Modal from "../Modal.vue";
import UserCount from "../Users/components/TotalUsers.vue";
import NewUserCount from "../Users/components/NewUsers.vue";
import ActiveUserCount from "../Users/components/ActiveUsers.vue";
import OnlineUserCount from "../Users/components/OnlineUsers.vue";

export default {
    components: {
        datatable: Datatable,
        pagination: Pagination,
        modal: Modal,
        'user-count': UserCount,
        'new-users': NewUserCount,
        'active-users': ActiveUserCount,
        'online-users': OnlineUserCount,
    },

  created() {
    this.getUsers();
  },

  data() {
    let sortOrders = {};
    let columns = [
      { width: "33%", label: "Name", name: "name" },
      { width: "33%", label: "Email", name: "email" },
        { width: "33%", label: "Role", name: "roles" },
      { width: "33%", label: "Actions", name: "actions" }
    ];
    columns.forEach(column => {
      sortOrders[column.name] = -1;
    });
    return {
      showDeleteUserModal: false,
      user: [],
      users: [],
      loadingTable: true,
      loadingDeleteUser: false,
      loadingUsers: true,
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
      this.loadingTable = true;
      this.tableData.draw++;
      axios
        .get(url, { params: this.tableData })
        .then(response => {
          let data = response.data;
          // console.log(data);
          if (this.tableData.draw == data.draw) {
            this.users = data.data.data;
            this.configPagination(data.data);
            this.loadingUsers = false;
            this.loadingTable = false;
          }
        })
        .catch(errors => {
          console.log(errors);
        });
    },
    refresh() {
      this.getUsers();
    },
    getUser(id) {
      axios.get("api/users/" + id).then(({ data }) => {
        this.user = data;
      });
    },
    deleteUser(user) {
      this.loadingDeleteUser = true;
      axios.delete("api/users/" + user.id)
        .then(response => {
          this.loadingDeleteUser = false;
          this.refresh();
          this.showDeleteUserModal = false;
          this.$notify({
            group: "users",
            title: "User Successfully Deleted",
            type: "success",
            text: user.name + " was successfully deleted."
          });
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
      this.loadingUsers = false;
    },
    getIndex(array, key, value) {
      return array.findIndex(i => i[key] == value);
    }
  }
};
</script>