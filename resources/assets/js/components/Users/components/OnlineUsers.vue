<template>
    <div class="info-box mb-3">
        <span class="info-box-icon bg-warning elevation-1">
            <i class="fa fa-user-check"></i>
        </span>
        <div class="info-box-content">
            <span class="info-box-text">
                Currently Online
                <i @click="getOnlineUsersCount()" class="fa fa-sync"></i>
            </span>
            <span class="info-box-number" v-if="loadingUsersOnlineCount">
                <i class="fa fa-spinner fa-spin"></i>
            </span>
            <span class="info-box-number" v-else>
                {{ onlineUsers }}
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
            this.getOnlineUsersCount();
        },

        data() {
            return {
                onlineUsers: 0,
                loadingUsersOnlineCount: true,
            };
        },

        methods: {
            getOnlineUsersCount() {
                this.loadingUsersOnlineCount = true;
                axios.get("api/count/users/online")
                .then(({ data }) => {
                    this.onlineUsers = data;
                    this.loadingUsersOnlineCount = false;
                });
            },
        }
    };
</script>