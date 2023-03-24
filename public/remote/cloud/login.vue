<template>
  <div class="form-page">
    <div class="form-container">
      <el-form :model="form" label-position="top">
        <el-form-item label="登录账号">
          <el-input v-model="form.username" placeholder="请输入云服务账号" />
        </el-form-item>
        <el-form-item label="登录密码">
          <el-input
            type="password"
            v-model="form.password"
            placeholder="请输入登录密码"
          />
        </el-form-item>
        <el-form-item label="验证码">
          <el-input v-model="form.scode" placeholder="请输入验证码">
            <template #suffix>
              <el-image :src="scodeSrc" @click="hanldScode" class="captcha"></el-image>
            </template>
          </el-input>
        </el-form-item>
        <div class="action-btn">
          <a href="http://kfadmin.net/user/#/register" target="_blank">注册账号</a>
          <a href="http://kfadmin.net/user/#/forgot" target="_blank">忘记密码</a>
        </div>
        <div class="text-center mt-3">
          <el-button type="primary" style="width: 100%" @click="onSubmit">
            立即登录
          </el-button>
        </div>
      </el-form>
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
      scodeSrc: "/api/admin/Cloud/captcha",
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
      _this.$http.usePost("/admin/cloud/login", _this.form).then((e) => {
        const { msg } = e;
        _this.openWin("remote/cloud/index");
        _this.$notifyMsg.useNotifySuccess(msg);
      });
    },
    // 切换验证码
    hanldScode() {
      const _this = this;
      _this.scodeSrc = `${_this.scodeSrc}?t=${Math.random()}`;
    },
    init() {
      // 检测是否已登录
      //   console.log("用户数据", this.$user);
    },
  },
};
</script>

<style lang="scss" scoped>
.form-page {
  display: flex;
  justify-content: center;
  align-items: center;
  padding-top: 50px;

  .form-container {
    width: 350px;
    margin: 0 auto;

    .captcha {
      width: 90px;
      height: 32px;
      cursor: pointer;
    }

    .action-btn {
      display: flex;
      justify-content: space-between;
    }
  }
}
</style>
