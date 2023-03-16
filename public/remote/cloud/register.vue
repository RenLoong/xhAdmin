<template>
  <div>
    <el-form :model="form" label-width="120px">
      <el-form-item label="手机号码">
        <el-input v-model="form.username" placeholder="手机号码（作为登录账号）" />
      </el-form-item>
      <el-form-item label="登录密码">
        <el-input v-model="form.password" placeholder="请输入登录密码" />
      </el-form-item>
      <el-form-item label="重复密码">
        <el-input v-model="form.vpassword" placeholder="请在次输入密码" />
      </el-form-item>
      <el-form-item label="站点域名">
        <el-input v-model="form.site_url" placeholder="请输入站点域名" />
      </el-form-item>
      <el-form-item label="站点名称">
        <el-input v-model="form.site_name" placeholder="请输入站点名称" />
      </el-form-item>
      <el-form-item label="真实姓名">
        <el-input v-model="form.contact" placeholder="请输入真实姓名" />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit">立即注册</el-button>
        <el-button type="danger" @click="openWin('remote/cloud')">返回登录</el-button>
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
        vpassword: "",
        site_url: "",
        site_name: "",
        contact: "",
        mobile: "",
      },
    };
  },
  props: {
    url: String,
  },
  created() {},
  methods: {
    openWin(path) {
      this.$emit("openWin", path);
    },
    onSubmit() {
      console.log("提交测试");
    },
  },
};
</script>

<style lang="scss" scoped></style>
