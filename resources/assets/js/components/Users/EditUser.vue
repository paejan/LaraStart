<template>
    <div class="container">
        <notifications group="errors" position="bottom right" :speed="2000"/>
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
                           {{ user.login_at }}
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
                <button type="button" class="btn btn-danger btn-lg"> <i class="fa fa-trash-alt"></i> Delete User </button>
                <button type="button" class="btn btn-primary btn-lg"> <i class="fa fa-user-plus"></i> New User </button>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="card card-primary">
                    <form v-on:submit="saveForm()">
                        <div class="card-header">
                            <h3 class="card-title col-12">
                                <i class="fa fa-user-edit"></i> Modify User
                            </h3>
                            <div class="card-tools">
                            
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name (*)</label>
                                <input type="text" required class="form-control" id="name" name="name" placeholder="Enter Name" :value="user.name">
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail (*)</label>
                                <input type="email" required class="form-control" id="email" name="email" placeholder="Enter E-mail" :value="user.email">
                            </div>
                            <div class="form-group">
                                <label for="password">New Password </label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter New Password" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="password_confirm">Confirm New Password</label>
                                <input type="password_confirm" class="form-control" id="password_confirm" name="password_confirm" placeholder="Confirm New Password" autocomplete="off">
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="image text-center">
                                        <img src="/img/profile.png" class="img-circle elevation-2" style="height: 2.7rem;" alt="User Image">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="profile_photo">Profile Photo </label>
                                        <input type="file" class="form-control" id="profile_photo" name="profile_photo">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button v-if="user" type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-6">
                <div class="card card-danger">
                    <div class="card-header">
                        <h3 class="card-title col-12">
                            <i class="fa fa-key"></i> Permissions
                        </h3>
                        <div class="card-tools">
                           
                        </div>
                    </div>
                    <div class="card-body">   
                        <div class="form-group">
                            <label for="user_group"></label>
                            <label for="user_group">User Group</label>
                            <select class="form-control" id="user_group" name="user_group">
                                <option value=""> User Disabled </option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button v-if="user" type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { format, formatDistance, formatRelative, subDays } from "date-fns";
export default {
  mounted() {
    // console.log("Component mounted.");
  },

  created() {
    this.getUser();
  },

  data() {
    return {
      loadingUser: true,
      user: [],
      format
    };
  },

  methods: {
    getUser() {
      this.loadingUser = true;
      axios
        .get("/api/user/" + this.$route.params.id)
        .then(({ data }) => {
          this.user = data;
          this.loadingUser = false;
        })
        .catch(errors => {
          console.log(errors);
          this.loadingUser = false;
          this.$notify({
            group: "errors",
            title: "Unable to load user data",
            type: "error",
            text: "Whoops..  We were unable to load that user."
          });
        });
    },
    formatDateTime: function(datetime) {
      // Formats a MySQL datetime to JS Datetime
      return new Date(datetime)
        .toISOString()
        .slice(0, 19)
        .replace("T", " ");
    },
    saveForm() {
      event.preventDefault();
      var app = this;
      axios
        .put("/api/users/" + this.$route.params.id)
        .then(function(resp) {
          app.$router.push({ path: "/" });
        })
        .catch(function(resp) {
          console.log(resp);
          this.$notify({
            group: "errors",
            title: "Unable to update user data",
            type: "error",
            text: "Whoops..  We were unable to update the user data."
          });
        });
    }
  }
};
</script>
