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
                            <div class="form-group">
                                <label for="permissions">Add Permission</label>
                                <select class="form-control" id="permissions[]" required v-model="role.permission">
                                    <option value="">Select Permission</option>
                                    <option v-for="permission in permissions" :value="permission.id">{{ permission.name }}</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-success">Add Another Permission</button>
                        </div>
                        <div class="card-footer">
                            <button v-if="loadingSave" disabled type="submit" class="btn btn-primary"><i class="fa fa-spinner fa-spin"></i> Save</button>
                            <button v-else type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            // console.log("Component mounted.");
        },

        created() {
            this.getPermissions();
        },

        data() {
            return {
                role: {
                    name: "",
                    permissions: [],
                },
                errors: {
                    name: "",
                    permissions: "",
                },
                permissions: "",
                loadingSave: false,
            };
        },

        methods: {
            getPermissions() {
                axios.get("/api/permissions")
                    .then(({ data }) => {
                        this.permissions = data;
                    });
            },
            saveForm() {
                event.preventDefault();
                this.loadingSaveUser = true;
                this.errors = { // Clear any previous errors.
                    name: "",
                    permissions: "",
                };
                axios.post("/api/roles", {
                    name: this.role.name,
                    permissions: this.role.permissions,
                }).then(response => {
                    Vue.notify({
                        group: "notifications",
                        title: "Role Created",
                        type: "success",
                        text: "This User Role has been created."
                    });
                    // this.$router.push({ path: "/role/" + response.data.id});
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
                            title: "Failed To Create Role!",
                            type: "error",
                            text: "There was a problem with your input."
                        });
                    });
            }
        }
    };
</script>
