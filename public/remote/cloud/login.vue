<template>
  <div class="form-page">
    <div class="form-container">
      <n-form :model="form" label-position="top">
        <n-form-item label="登录账号">
          <n-input v-model:value="form.username" placeholder="请输入云服务账号" />
        </n-form-item>
        <n-form-item label="登录密码">
          <n-input type="password" v-model:value="form.password" placeholder="请输入登录密码" />
        </n-form-item>
        <n-form-item label="验证码">
          <n-input-group>
            <n-input v-model:value="form.scode" placeholder="请输入验证码">
            </n-input>
            <n-image :src="captcha" @click="getCaptcha()" :preview-disabled="true" class="captcha" />
          </n-input-group>
        </n-form-item>
        <div class="action-btn">
          <a href="http://kfadmin.net/user/#/register" target="_blank">注册账号</a>
          <a href="http://kfadmin.net/user/#/forgot" target="_blank">忘记密码</a>
        </div>
        <div class="submit-button">
          <n-button type="primary" block @click="onSubmit">
            立即登录
          </n-button>
        </div>
      </n-form>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        username: "",
        password: "",
        scode: "",
      },
      scodeSrc: "/admin/PluginCloud/captcha",
      captcha: '',
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
    onSubmit() {
      var _this = this;
      _this.$http.usePost("admin/PluginCloud/login", _this.form)
        .then((e) => {
          const { msg } = e;
          _this.openWin("remote/cloud/index");
          _this.$notifyMsg.useNotification(msg);
        });
    },
    // 获取验证码
    getCaptcha() {
      var _this = this;
      _this.$http.useGet(`${_this.scodeSrc}?t=${Math.random()}`).then((res) => {
        const { data } = res
        _this.captcha = data
      })
    },
    init() {
      // 检测是否已登录
      //   console.log("用户数据", this.$user);
      this.getCaptcha();
    },
  },
};
</script>

<style lang="scss" scoped>
.form-page {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;

  .form-container {
    margin: 0 auto;
    width: 450px;

    .captcha {
      width: 90px;
      height: 32px;
      cursor: pointer;
    }

    .action-btn {
      display: flex;
      justify-content: space-between;
    }

    .submit-button {
      margin-top: 20px;
    }
  }
}
</style>
