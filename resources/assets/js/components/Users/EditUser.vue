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
                            <i class="fa fa-sync"></i>
                        </span>
                        <span class="info-box-number" v-if="loadingLastOnline">
                            <i class="fa fa-spinner fa-spin"></i>
                        </span>
                        <span class="info-box-number" v-else>
                           
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
                            <i @click="getNewUserCount()" class="fa fa-sync"></i>
                        </span>
                        <span class="info-box-number" v-if="loadingLastModified">
                            <i class="fa fa-spinner fa-spin"></i>
                        </span>
                        <span class="info-box-number" v-else>
                            
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
                            <i class="fa fa-sync"></i>
                        </span>
                        <span class="info-box-number" v-if="loadingUserCreated">
                            <i class="fa fa-spinner fa-spin"></i>
                        </span>
                        <span class="info-box-number" v-else>

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
        </div>

        <div class="row">
            <div class="col-6">
                <div class="card card-primary">
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
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter New Password">
                        </div>
                        <div class="form-group">
                            <label for="password_confirm">Confirm New Password</label>
                            <input type="password_confirm" required class="form-control" id="password_confirm" name="password_confirm" placeholder="Confirm New Password">
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
                                    <input type="file" required class="form-control" id="profile_photo" name="profile_photo">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        
                    </div>
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
    this.getUser();
  },

  data() {
    return {
      loadingLastOnline: true,
      loadingLastModified: true,
      loadingUserCreated: true,
      user: []
    };
  },

  methods: {
    getUser(id) {
      axios
        .get("/api/user/" + this.$route.params.id)
        .then(({ data }) => {
          this.user = data;
        })
        .catch(errors => {
          console.log(errors);
          this.$notify({
            group: "errors",
            title: "Unable to load user data",
            type: "error",
            text: "Whoops..  We were unable to load that user."
          });
        });
    }
  }
};
</script>
