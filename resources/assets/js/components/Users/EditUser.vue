<template>
    <div class="container">
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
    </div>
</template>

<script>
export default {
  mounted() {
    // console.log("Component mounted.");
  },

  data() {
    return {
      loadingLastOnline: true,
      loadingLastModified: true,
      loadingUserCreated: true
    };
  }
};
</script>
