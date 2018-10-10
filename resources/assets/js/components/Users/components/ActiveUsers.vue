<template>
    <div class="info-box mb-3">
        <span class="info-box-icon bg-primary elevation-1">
            <i class="fa fa-user-check"></i>
        </span>
        <div class="info-box-content">
            <span class="info-box-text">
                Active Users
            </span>
            <span class="info-box-number" v-if="loadingActiveUserCount">
                <i class="fa fa-spinner fa-spin"></i>
            </span>
            <span class="info-box-number" v-else>
                {{ ((activeUserCount / userCount) * 100).toFixed() }}
                <small>%</small>
            </span>
            <i @click="getActiveUserCount()" class="fa fa-sync"></i>
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
            this.getActiveUserCount();
            this.getUserCount();
            this.timer();
        },

        data() {
            return {
                activeUserCount: 0,
                userCount: 0,
                loadingActiveUserCount: true,
                lastUpdate: null,
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
                    this.lastUpdate = 0;
                });
            },
            timer() {
                setInterval(function () {
                    this.lastUpdate++;
                }.bind(this), 1000);
            },
        }
    };
</script>