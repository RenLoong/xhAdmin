<template>
  <div>
    <el-form :model="form" label-width="120px">
      <el-form-item label="手机号码">
        <el-input v-model="form.username" placeholder="请输入手机号码" />
      </el-form-item>
      <el-form-item label="登录密码">
        <el-input v-model="form.password" placeholder="请输入登录密码" />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit">立即登录</el-button>
        <el-button type="danger" @click="openWin('remote/cloud/register')"
          >立即注册</el-button
        >
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        username: "",
        password: "",
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
    onSubmit() {
      var _this = this;
      _this.$http.usePost("/kfadmin/cloud/login").then((e) => {
        const { msg } = e;
        _this.openWin("remote/cloud/index");
        _this.$notifyMsg.useNotifySuccess(msg);
      });
    },
    init() {
      // 检测是否已登录
      //   console.log("用户数据", this.$user);
    },
  },
};
</script>

<style lang="scss" scoped></style>
