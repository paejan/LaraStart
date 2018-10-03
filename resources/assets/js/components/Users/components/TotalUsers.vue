<template>
    <div class="info-box">
            <span class="info-box-icon bg-info elevation-1">
                <i class="fa fa-users"></i>
            </span>
        <div class="info-box-content">
            <span class="info-box-text">
                Total Users
            </span>
            <span class="info-box-number" v-if="loadingUserCount">
                <i class="fa fa-spinner fa-spin"></i>
            </span>
            <span class="info-box-number" v-else>
                {{ userCount.toLocaleString('en') }}
            </span>
            <i @click="getUserCount()" class="fa fa-sync"></i>
            <small v-if="lastUpdate < 60">Updated: Just Now</small>
            <small v-else-if="lastUpdate === null">Updated: <i class="fa fa-spinner fa-spin"></i></small>
            <small v-else-if="(lastUpdate > 60) && (lastUpdate < 120)">Updated: 1 minute ago</small>
            <small v-else>Updated: {{ Number(lastUpdate/60).toFixed(0) }} minutes ago</small>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
           // console.log('Component mounted.')
        },

        created() {
            this.getUserCount();
            this.timer();
        },

        data() {
            return {
                userCount: 0,
                loadingUserCount: true,
                lastUpdate: null,
            };
        },

        methods: {
            getUserCount() {
                this.lastUpdate = null;
                this.loadingUserCount = true;
                axios.get("api/count/users")
                .then(({ data }) => {
                    this.userCount = data;
                    this.loadingUserCount = false;
                    this.lastUpdate = 0;
                });
            },
            timer() {
                setInterval(function () {
                    this.lastUpdate++;
                }.bind(this), 1000);
            }
        },


    };
</script>