<template>
    <div class="container">
        <notifications group="notifications" position="bottom right" :speed="2000"/>
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3 class="m-0 text-dark">
                    Edit Role
                </h3>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <form v-on:submit="saveForm()" >
                        <div class="card-header">
                            <h3 class="card-title col-12">
                                <i class="fa fa-key"></i> {{ role.name }}
                            </h3>
                            <div class="card-tools">

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group has-error" v-if="errors.name">
                                <label for="name">Name (*)</label>
                                <input type="text" required class="form-control" id="name" name="name" placeholder="Enter Name" v-model="role.name">
                                <span class="help-block">{{ errors.name }}</span>
                            </div>
                            <div class="form-group" v-else>
                                <label for="name">Name (*)</label>
                                <input type="text" required class="form-control" id="name" name="name" placeholder="Enter Name" v-model="role.name">
                            </div>
                            <label>Permissions</label>
                            <div class="form-group" v-for="permission in permissions">
                                <input type="checkbox" v-model="role.permissions" :value="permission.id"> {{ permission.name }}
                            </div>
                        </div>
                        <div class="card-footer">
                            <button v-if="loadingSave" disabled type="submit" class="btn btn-primary"><i class="fa fa-spinner fa-spin"></i> Save</button>
                            <button v-else type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-danger" @click="showDeleteRoleModal = true">Delete Role</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Delete Role Modal -->
        <modal v-if="showDeleteRoleModal">
            <template slot="modal-title">Deleting: {{ role.name }}</template>
            <template slot="modal-close">
                <button type="button" class="close" @click="showDeleteRoleModal = false" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </template>
            <template slot="modal-body"> Are you sure you want to delete {{ role.name }}? </template>
            <template slot="modal-footer">
                <button type="button" class="btn btn-secondary" @click="showDeleteRoleModal = false">Close</button>

                    <button v-if="loadingDelete" disabled type="button" class="btn btn-danger"><i class="fa fa-sync fa-spin"></i></button>
                    <button v-else @click="deleteRole(role)" type="button" class="btn btn-danger">Delete Role</button>

            </template>
        </modal>
        <!-- /.Delete Role Modal -->
    </div>
</template>

<script>
    import Modal from "../Modal.vue";

    export default {
        components: {
            modal: Modal,
        },

        created() {
            this.getPermissions();
            this.getRole();
        },

        data() {
            return {
                role: {
                    id: "",
                    name: "",
                    permissions: [],
                },
                errors: {
                    name: "",
                    permissions: "",
                },
                permissions: "",
                showDeleteRoleModal: false,
                loadingDelete: false,
                loadingSave: false,
                loadingRole: false,
            };
        },

        methods: {
            getPermissions() {
                axios.get("/api/permissions")
                    .then(({ data }) => {
                        this.permissions = data;
                    });
            },
            getRole() {
                this.loadingRole = true;
                axios.get("/api/roles/" + this.$route.params.id)
                .then(({ data }) => {
                    this.role.id = data.id;
                    this.role.name = data.name;
                    for(var i = 0; i < data.permissions.length; i++) {
                        this.role.permissions.push(data.permissions[i].id);
                    }
                })
                .catch(errors => {
                    console.log(errors);
                    this.loadingUser = false;
                    Vue.notify({
                        group: "notifications",
                        title: "Unable to load role.",
                        type: "error",
                        text: "Whoops..  We were unable to load that role."
                    });
                });
            },
            saveForm() {
                event.preventDefault();
                this.loadingSaveUser = true;
                this.errors = { // Clear any previous errors.
                    name: "",
                    permissions: "",
                };
                axios.patch("/api/roles/" + this.$route.params.id, {
                    name: this.role.name,
                    permissions: this.role.permissions
                })
                .then(response => {
                    Vue.notify({
                        group: "notifications",
                        title: "Role Updated",
                        type: "success",
                        text: "This User Role has been updated."
                    });
                })
                .catch(error => {
                    this.loadingSave = false;
                    if (error.response) {
                        if (error.response.data.errors.name) {
                            this.errors.name = error.response.data.errors.name[0];
                        }
                        console.log(error.response);
                    } else {
                        console.log(error);
                    }
                    Vue.notify({
                        group: "notifications",
                        title: "Failed To Update Role!",
                        type: "error",
                        text: "There was a problem with your input."
                    });
                });
            },
            removePermission (key) {
                this.$delete(this.role.permissions, key)
            },
            deleteRole(role) {
                this.loadingDelete = true;
                axios.delete("/api/roles/" + role.id)
                    .then(response => {
                        this.loadingDelete = false;
                        this.showDeleteRoleModal = false;
                        // this.$router.push({ path: "/roles" });
                    })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
        }
    };
</script>
