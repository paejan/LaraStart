<template>
    <div class="info-box mb-3">
        <span class="info-box-icon bg-primary elevation-1">
            <i class="fa fa-user-check"></i>
        </span>
        <div class="info-box-content">
            <span class="info-box-text">
                Active Users
                <i @click="getActiveUserCount()" class="fa fa-sync"></i>
            </span>
            <span class="info-box-number" v-if="loadingActiveUserCount">
                <i class="fa fa-spinner fa-spin"></i>
            </span>
            <span class="info-box-number" v-else>
                {{ ((activeUserCount / userCount) * 100).toFixed() }}
                <small>%</small>
            </span>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            // console.log('Component mounted.')
        },

        created() {
            this.getActiveUserCount();
            this.getUserCount();
        },

        data() {
            return {
                activeUserCount: 0,
                userCount: 0,
                loadingActiveUserCount: true,
            };
        },

        methods: {
            getUserCount() {
                axios.get("api/count/users")
                .then(({ data }) => {
                    this.userCount = data;
                });
            },
            getActiveUserCount() {
                this.loadingActiveUserCount = true;
                axios.get("api/count/users/active")
                .then(({ data }) => {
                    this.activeUserCount = data;
                    this.loadingActiveUserCount = false;
                });
            },
        }
    };
</script>