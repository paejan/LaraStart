<template>
    <div class="container">
        <notifications group="notifications" position="bottom right" :speed="2000"/>
        <div class="row">
            <div class="col-8">
                <div class="card card-primary">
                    <form v-on:submit="saveForm()" >
                        <div class="card-header">
                            <h3 class="card-title col-12">
                                <i class="fa fa-user-edit"></i> Your Profile
                            </h3>
                            <div class="card-tools">

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group has-error" v-if="errors.name">
                                <label for="name">Name (*)</label>
                                <input type="text" required class="form-control" id="name" name="name" placeholder="Enter Name" v-model="profile.name">
                                <span class="help-block">{{ errors.name }}</span>
                            </div>
                            <div class="form-group" v-else>
                                <label for="name">Name (*)</label>
                                <input type="text" required class="form-control" id="name" name="name" placeholder="Enter Name" v-model="profile.name">
                            </div>
                            <div class="form-group has-error" v-if="errors.email">
                                <label for="email">E-mail (*)</label>
                                <input type="email" required class="form-control" id="email" name="email" placeholder="Enter E-mail" v-model="profile.email">
                                <span class="help-block">{{ errors.email }}</span>
                            </div>
                            <div class="form-group" v-else>
                                <label for="email">E-mail (*)</label>
                                <input type="email" required class="form-control" id="email" name="email" placeholder="Enter E-mail" v-model="profile.email">
                            </div>
                            <div class="form-group has-error" v-if="errors.password">
                                <label for="password">New Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter New Password" autocomplete="off" v-model="profile.new_password">
                                <span class="help-block">{{ errors.password }}</span>
                            </div>
                            <div class="form-group" v-else>
                                <label for="password">New Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter New Password" autocomplete="off" v-model="profile.new_password">
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Confirm New Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm New Password" autocomplete="off" v-model="profile.password_confirmation">
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="image text-center" v-if="profile_photo">
                                        <img :src="profile_photo" class="img-circle" style="height: 4rem; width: 4rem; margin-right: 10px;">
                                    </div>
                                    <div class="image text-center" v-else>
                                        <img :src="profile.profile_photo" class="img-circle elevation-2" style="height: 4rem; width: 4rem; margin-right: 10px;" alt="User Image">
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
        created() {
            this.getProfile();
        },

        data() {
            return {
                loadingUser: true,
                profile: {
                    name: "",
                    email: "",
                    password: "",
                    password_confirmation: ""
                },
                roles: [],
                errors: {
                    name: "",
                    email: "",
                    password: "",
                    profile_photo: "",
                },
                loadingSave: true,
                profile_photo: "",
            };
        },

        methods: {
            getProfile() {
                this.loadingSave = true;
                axios
                    .get("/api/profile/")
                    .then(({ data }) => {
                        this.profile = data;
                        this.loadingSave = false;
                    })
                    .catch(errors => {
                        console.log(errors);
                        this.loadingSave = false;
                        Vue.notify({
                            group: "notifications",
                            title: "Unable to load profile",
                            type: "error",
                            text: "Whoops..  We were unable to load your information."
                        });
                    });
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
            saveForm() {
                event.preventDefault();
                this.loadingSave = true;
                this.errors = { // Clear any previous errors.
                    name: "",
                    email: "",
                    password: "",
                    profile_photo: ""
                };
                axios.patch("/api/profile/", {
                    name: this.profile.name,
                    email: this.profile.email,
                    password: this.profile.new_password,
                    password_confirmation: this.profile.password_confirmation,
                    profile_photo: this.profile_photo,
                }).then(response => {
                    this.getProfile();
                    Vue.notify({
                        group: "notifications",
                        title: "Profile Updated",
                        type: "success",
                        text: "Your profile has been updated."
                    });
                }).catch(error => {
                    this.loadingSave = false;
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
        }
    };
</script>
