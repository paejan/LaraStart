<template>
    <div class="container">
        <notifications group="notifications" position="bottom right" :speed="2000"/>
        <div class="row">
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1">
                        <i class="fa fa-user-check"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">
                            Last Online
                        </span>
                        <span class="info-box-number" v-if="user.login_at">                       
                           {{ format(formatDateTime(user.login_at), 'MMM Do YYYY h:mm a') }}
                        </span>
                        <span class="info-box-number" v-else>                       
                           Never Logged In
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1">
                        <i class="fa fa-user-edit"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">
                            Last Modified
                        </span>
                        <span class="info-box-number" v-if="user.updated_at">
                            {{ format(formatDateTime(user.updated_at), 'MMM Do YYYY h:mm a') }}
                        </span>
                        <span class="info-box-number" v-else>
                            <i class="fa fa-spinner fa-spin"></i>
                        </span>
                    </div>
                </div>
            </div>
            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-primary elevation-1">
                        <i class="fa fa-user-plus"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">
                            User Created
                        </span>
                        <span class="info-box-number" v-if="user.created_at">
                            {{ format(formatDateTime(user.created_at), 'MMM Do YYYY h:mm a') }}
                        </span>
                        <span class="info-box-number" v-else>
                            <i class="fa fa-spinner fa-spin"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-sm-6">
                <h3 class="m-0 text-dark">
                    {{ user.name }}
                </h3>
            </div>
            <div class="col-sm-6 text-right">
                <button @click="showDeleteUserModal = true" type="button" class="btn btn-danger btn-lg"> <i class="fa fa-trash-alt"></i> Delete User </button>
                <router-link :to="{ name : 'new_user' }">
                    <button type="button" class="btn btn-primary btn-lg"> <i class="fa fa-user-plus"></i> New User </button>
                </router-link>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="card card-primary">
                    <form v-on:submit="saveUserForm()" >
                        <div class="card-header">
                            <h3 class="card-title col-12">
                                <i class="fa fa-user-edit"></i> Modify User
                            </h3>
                            <div class="card-tools">
                            
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group has-error" v-if="errors.name">
                                <label for="name">Name (*)</label>
                                <input type="text" required class="form-control" id="name" name="name" placeholder="Enter Name" v-model="user.name">
                                <span class="help-block">{{ errors.name }}</span>
                            </div>
                            <div class="form-group" v-else>
                                <label for="name">Name (*)</label>
                                <input type="text" required class="form-control" id="name" name="name" placeholder="Enter Name" v-model="user.name">
                            </div>
                            <div class="form-group has-error" v-if="errors.email">
                                <label for="email">E-mail (*)</label>
                                <input type="email" required class="form-control" id="email" name="email" placeholder="Enter E-mail" v-model="user.email">
                                <span class="help-block">{{ errors.email }}</span>
                            </div>
                            <div class="form-group" v-else>
                                <label for="email">E-mail (*)</label>
                                <input type="email" required class="form-control" id="email" name="email" placeholder="Enter E-mail" v-model="user.email">
                            </div>
                            <div class="form-group has-error" v-if="errors.password">
                                <label for="password">New Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter New Password" autocomplete="off" v-model="user.new_password">
                                <span class="help-block">{{ errors.password }}</span>
                            </div>
                            <div class="form-group" v-else>
                                <label for="password">New Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter New Password" autocomplete="off" v-model="user.new_password">
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Confirm New Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm New Password" autocomplete="off" v-model="user.password_confirmation">
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="image text-center" v-if="profile_photo">
                                        <img :src="profile_photo" class="img-circle" style="height: 4rem; width: 4rem; margin-right: 10px;">
                                    </div>
                                    <div class="image text-center" v-else>
                                        <img :src="user.profile_photo" class="img-circle elevation-2" style="height: 4rem; width: 4rem; margin-right: 10px;" alt="User Image">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="profile_photo">Profile Photo </label>
                                        <input type="file" class="form-control" id="profile_photo" name="profile_photo"  v-on:change="onImageChange">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button v-if="loadingSaveUser" disabled type="submit" class="btn btn-primary"><i class="fa fa-spinner fa-spin"></i> Save</button>
                            <button v-else type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-6">
                <div class="card card-danger">
                    <form v-on:submit="saveRoleForm()" >
                        <div class="card-header">
                            <h3 class="card-title col-12">
                                <i class="fa fa-key"></i> Permissions
                            </h3>
                            <div class="card-tools">

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="user_group">User Group</label>
                                <select class="form-control" id="user_group" required name="user_group" v-model="user_group">
                                    <option value="">None</option>
                                    <option v-for="role in roles" :value="role.id">{{ role.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button v-if="loadingSaveRole" disabled type="submit" class="btn btn-primary"><i class="fa fa-spinner fa-spin"></i> Save</button>
                            <button v-else type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
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
import { format, formatDistance, formatRelative, subDays } from "date-fns";
import Modal from "../Modal.vue";

export default {
    components: {
        modal: Modal
    },

    mounted() {
        // console.log("Component mounted.");
    },

    created() {
        this.getUser();
        this.getRoles();
    },

    data() {
        return {
            loadingUser: true,
            user: [],
            roles: [],
            format,
            errors: {
                name: "",
                email: "",
                password: "",
                profile_photo: "",
                role: ""
            },
            loadingSaveUser: true,
            loadingSaveRole: true,
            profile_photo: "",
            user_group: "",
            showDeleteUserModal: false,
            loadingDeleteUser: false,
        };
    },

    methods: {
        getUser() {
            this.loadingUser = true;
            axios
            .get("/api/users/" + this.$route.params.id)
            .then(({ data }) => {
                this.user = data;
                this.loadingUser = false;
                this.loadingSaveUser = false;
            })
            .catch(errors => {
                console.log(errors);
                this.loadingUser = false;
                Vue.notify({
                    group: "notifications",
                    title: "Unable to load user data",
                    type: "error",
                    text: "Whoops..  We were unable to load that user."
                });
            });
        },
        getRoles() {
            axios.get("/api/roles")
                .then(({ data }) => {
                    this.roles = data
                    this.loadingSaveRole = false;
                })
                .catch(errors => {
                    console.log(errors);
                    Vue.notify({
                        group: "notifications",
                        title: "Unable to User Roles",
                        type: "error",
                        text: "Whoops..  We were unable to load user roles."
                    });
                })
        },
        onImageChange(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length) return;
            this.createImage(files[0]);
        },
        createImage(file) {
            let reader = new FileReader();
            let vm = this;
            reader.onload = e => {
                vm.profile_photo = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        formatDateTime: function(datetime) {
            // Formats a MySQL datetime to JS Datetime
            return new Date(datetime)
                .toISOString()
                .slice(0, 19)
                .replace("T", " ");
         },
        saveUserForm() {
            event.preventDefault();
            this.loadingSaveUser = true;
            this.errors = { // Clear any previous errors.
                name: "",
                email: "",
                password: "",
                profile_photo: ""
            };
            var app = this;
            axios.patch("/api/users/" + this.$route.params.id, {
                name: this.user.name,
                email: this.user.email,
                password: this.user.new_password,
                password_confirmation: this.user.password_confirmation,
                profile_photo: app.profile_photo,
            })
            .then(response => {
                this.getUser();
                Vue.notify({
                    group: "notifications",
                    title: "User Updated",
                    type: "success",
                    text: "This user has been updated."
                });
            })
            .catch(error => {
                this.loadingSaveUser = false;
                if (error.response) {
                    if (error.response.data.errors.name) {
                        this.errors.name = error.response.data.errors.name[0];
                    }
                    if (error.response.data.errors.email) {
                        this.errors.email = error.response.data.errors.email[0];
                    }
                    if (error.response.data.errors.password) {
                        this.errors.password = error.response.data.errors.password[0];
                    }
                    console.log(error.response);
                } else {
                    console.log(error);
                }
                Vue.notify({
                    group: "notifications",
                    title: "Failed To Update!",
                    type: "error",
                    text: "There was a problem with your input."
                });
            });
        },
        saveRoleForm() {
            event.preventDefault();
            this.loadingSaveRole = true;
            this.errors = { // Clear any previous errors.
                role: ""
            };
            console.log(this.user_group);
            axios.post("/api/user/update_role/" + this.$route.params.id, {
                user_group: this.user_group,
            })
            .then(response => {
                // app.$router.push({ path: "/" });
                this.getUser();
                this.getRoles();
                Vue.notify({
                    group: "notifications",
                    title: "User Role Updated.",
                    type: "success",
                    text: "This user's User Role has been updated."
                });
            })
            .catch(error => {
                this.loadingSaveRole = false;
                if (error.response) {
                    console.log(error.response);
                } else {
                    console.log(error);
                }
                Vue.notify({
                    group: "notifications",
                    title: "Failed To Update!",
                    type: "error",
                    text: "There was a problem with your input."
                });
            });
        },
        deleteUser(user) {
            this.loadingDeleteUser = true;
            axios.delete("/api/users/" + user.id)
            .then(response => {
                this.loadingDeleteUser = false;
                this.showDeleteUserModal = false;
                this.$notify({
                    group: "users",
                    title: "User Successfully Deleted",
                    type: "success",
                    text: user.name + " was successfully deleted."
                });
                // this.$router.push({ path: "/users" });
            })
            .catch(errors => {
                console.log(errors);
            });
        },
    }
};
</script>
