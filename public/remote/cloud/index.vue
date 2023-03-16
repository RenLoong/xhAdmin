<template>
  <div class="cloud-container">
    <div class="user-center" v-if="isLogin">云服务中心</div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isLogin: false,
      user: {
        username: "",
      },
    };
  },
  props: {
    url: String,
  },
  created() {
    this.init();
  },
  methods: {
    openWin(path) {
      this.$emit("openWin", path);
    },
    init() {
      var _this = this;
      _this.$http
        .useGet("/kfadmin/cloud/user")
        .then((e) => {
          _this.isLogin = true;
        })
        .catch((err) => {
          if (err?.code == 12000) {
            _this.openWin("remote/cloud/login");
          }
          console.log(err);
        });
    },
  },
};
</script>

<style lang="scss" scoped></style>
