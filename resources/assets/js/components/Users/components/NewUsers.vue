<template>
    <div class="info-box mb-3">
        <span class="info-box-icon bg-success elevation-1">
            <i class="fa fa-user-plus"></i>
        </span>
        <div class="info-box-content">
            <span class="info-box-text">
                New Users
            </span>
            <span class="info-box-number" v-if="loadingNewUserCount">
                <i class="fa fa-spinner fa-spin"></i>
            </span>
            <span class="info-box-number" v-else>
                {{ newUserCount.toLocaleString('en') }}
            </span>
            <i @click="getNewUserCount()" class="fa fa-sync"></i>
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
            this.getNewUserCount();
            this.timer();
        },

        data() {
            return {
                newUserCount: 0,
                loadingNewUserCount: true,
                lastUpdate: null,
            };
        },

        methods: {
            getNewUserCount() {
                this.lastUpdate = null;
                this.loadingNewUserCount = true;
                axios.get("api/users/count/new")
                    .then(({ data }) => {
                        this.newUserCount = data;
                        this.loadingNewUserCount = false;
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