<template>
    <div class="roles container">
        <notifications group="permissions" position="bottom right" :speed="2000"/>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title col-6">
                            Roles
                            <div class="input-group">
                                <input type="text" class="form-control" v-model="tableData.search" @input="getRoles()" placeholder="Search Roles .." aria-label="Search Roles" aria-describedby="roleSearch">
                                <div class="input-group-prepend">
                                    <div class="input-group-text" id="groupSearch"><i class="fa fa-search"></i></div>
                                </div>
                                <div class="input-group-prepend">
                                    <div v-if="loadingRoles" class="input-group-text" id="roleSearch"><i class="fa fa-sync fa-spin"></i></div>
                                    <div v-else @click="getRoles()" class="input-group-text" id="roleSearch"><i class="fa fa-sync"></i></div>
                                </div>
                            </div>
                        </h3>
                        <div class="card-tools">
                            <router-link :to="{ name : 'new_role' }">
                                <button type="button" class="btn btn-primary btn-lg"> <i class="fa fa-user-plus"></i> New Role </button>
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
                            </tr>
                            </tbody>
                            <tbody v-else>
                            <tr v-for="role in roles" :key="role.id">
                                <td>{{ role.name }}</td>
                                <td>{{ role.users.length}} </td>
                                <td>
                                    <router-link :to="{ name : 'edit_role', params : { id : role.id } }">
                                        <button type="button" class="btn btn-outline-primary btn-sm"><i class="fa fa-user-edit"></i> Edit Role</button>
                                    </router-link>
                                    <button @click="getRoleUsers(role.id); showRoleUsersModal = true" type="button" class="btn btn-outline-success btn-sm"><i class="fa fa-users"></i> View Users</button>
                                    <button @click="getRole(role.id); showDeleteRoleModal = true;" type="button" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash-alt"></i> Delete Role</button>
                                </td>
                            </tr>
                            </tbody>
                        </datatable>
                    </div>
                    <div class="card-footer">
                        <pagination :pagination="pagination"
                                    @prev="getRoles(pagination.prevPageUrl)"
                                    @next="getRoles(pagination.nextPageUrl)">
                        </pagination>
                    </div>
                </div>
            </div>
        </div>
        <!-- Roles Assigned Users Modal -->
        <modal v-if="showRoleUsersModal">
            <template slot="modal-title">{{ role.name }} Users</template>
            <template slot="modal-close">
                <button type="button" class="close" @click="showRoleUsersModal = false" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </template>
            <template slot="modal-body" v-if="role.users">
                <ul v-if="role.users.length > 0">
                    <li v-for="user in role.users">{{user.name }} ({{ user.email}})</li>
                </ul>
                <ul v-else>
                    <li> No users assigned to {{ role.name }}. </li>
                </ul>
            </template>
            <template slot="modal-footer">
                <button type="button" class="btn btn-secondary" @click="showRoleUsersModal = false">Close</button>
            </template>
        </modal>
        <!-- /.Roles Assigned Users Modal -->
        <!-- Delete Role Modal -->
        <modal v-if="showDeleteRoleModal">
            <template slot="modal-title">Deleting: {{ role.name }}</template>
            <template slot="modal-close">
                <button type="button" class="close" @click="showDeleteRoleModal = false" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </template>
            <template slot="modal-body" v-if="role.users">
                <p v-if="role.users.length > 0"> 
                    {{ role.users.length }} users are assigned to this role. <br>
                    Please assign them to another role before deleting this permission role. <br>
                    <ul v-if="role.users">
                        <li v-for="user in role.users">
                            {{user.name }} ({{ user.email}})
                        </li>
                    </ul>
                </p>
                <p v-else> 
                    Are you sure you want to delete {{ role.name }}?
                </p>
            </template>
            <template slot="modal-body" v-else> Are you sure you want to delete {{ role.name }}? </template>
            <template slot="modal-footer">
                <button type="button" class="btn btn-secondary" @click="showDeleteRoleModal = false">Close</button>
                <div v-if="role.users && role.users.length === 0">
                    <button v-if="loadingDelete" disabled type="button" class="btn btn-danger"><i class="fa fa-sync fa-spin"></i></button>
                    <button v-else @click="deleteRole(role)" type="button" class="btn btn-danger">Delete Role</button>
                </div>
            </template>
        </modal>
        <!-- /.Delete Role Modal -->
    </div>
</template>

<script>
    import Datatable from "../DataTable.vue";
    import Pagination from "../Pagination.vue";
    import Modal from "../Modal.vue";

    export default {
        components: {
            datatable: Datatable,
            pagination: Pagination,
            modal: Modal,
        },

        created() {
            this.getRoles();
        },

        data() {
            let sortOrders = {};
            let columns = [
                { width: "33%", label: "Role", name: "name" },
                { width: "33%", label: "Users", name: "users" },
                { width: "33%", label: "Actions", name: "actions" }
            ];
            columns.forEach(column => {
                sortOrders[column.name] = -1;
            });
            return {
                showDeleteRoleModal: false,
                role: [],
                roles: [],
                loadingTable: true,
                loadingDelete: false,
                showRoleUsersModal: false,
                loadingRoles: true,
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
            getRoles(url = "api/roles") {
                this.loadingRoles = true;
                this.loadingTable = true;
                this.tableData.draw++;
                axios.get(url, { params: this.tableData })
                    .then(response => {
                        let data = response.data;
                        if (this.tableData.draw == data.draw) {
                            this.roles = data.data.data;
                            this.configPagination(data.data);
                            this.loadingRoles = false;
                            this.loadingTable = false;
                        }
                    })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            getRoleUsers(role_id) {
                axios.get("api/roles/users/" + role_id).then(({ data }) => {
                    this.role = data;
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
                this.getRoles();
                this.loadingRoles = false;
            },
            getIndex(array, key, value) {
                return array.findIndex(i => i[key] == value);
            },
            getRole(id) {
                axios.get("api/roles/users/" + id).then(({ data }) => {
                    this.role = data;
                });
            },
            deleteRole(role) {
                this.loadingDelete = true;
                axios.delete("api/roles/" + role.id)
                    .then(response => {
                        this.loadingDelete = false;
                        this.getRoles();
                        this.showDeleteRoleModal = false;
                        this.$notify({
                            group: "permissions",
                            title: "Role Successfully Deleted",
                            type: "success",
                            text: role.name + " was successfully deleted."
                        });
                    })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
        }
    };
</script>