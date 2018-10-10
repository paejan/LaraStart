<template>
    <div class="info-box mb-3">
        <span class="info-box-icon bg-warning elevation-1">
            <i class="fa fa-user-check"></i>
        </span>
        <div class="info-box-content">
            <span class="info-box-text">
                Currently Online
            </span>
            <span class="info-box-number" v-if="loadingUsersOnlineCount">
                <i class="fa fa-spinner fa-spin"></i>
            </span>
            <span class="info-box-number" v-else>
                {{ onlineUsers }}
            </span>
            <i @click="getOnlineUsersCount()" class="fa fa-sync"></i>
            <small v-if="lastUpdate < 60">Updated: Just Now</small>
            <small v-else-if="lastUpdate === null">Updated: <i class="fa fa-spinner fa-spin"></i></small>
            <small v-else-if="(lastUpdate > 59) && (lastUpdate < 120)">Updated: 1 minute ago</small>
            <small v-else>Updated: {{ Math.floor((lastUpdate/60)) }} minutes ago</small>
        </div>
    </div>
</template>

<script>
    export default {
        created() {
            this.getOnlineUsersCount();
            this.timer();
        },

        data() {
            return {
                onlineUsers: 0,
                loadingUsersOnlineCount: true,
                lastUpdate: null,
            };
        },

        methods: {
            getOnlineUsersCount() {
                this.lastUpdate = null;
                this.loadingUsersOnlineCount = true;
                axios.get("api/count/users/online")
                .then(({ data }) => {
                    this.onlineUsers = data;
                    this.loadingUsersOnlineCount = false;
                    this.lastUpdate = 0;
                });
            },
            timer() {
                setInterval(function () {
                    this.lastUpdate++;
                }.bind(this), 1000);
            }
        }
    };
</script>