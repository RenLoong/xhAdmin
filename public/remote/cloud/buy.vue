<template>
  <div class="page-container">
    <!-- 应用信息 -->
    <el-form :model="form" label-position="top" class="form-container">
      <el-row :gutter="20">
        <el-col :span="12">
          <el-form-item label="应用名称">
            {{ form.title }}
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="应用版本">
            {{ form.version }}
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="平台类型">
            {{ form.platform }}
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="应用类型">
            {{ form.plugin_type }}
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="开发者名称">
            {{ form.author.title }}
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="应用图标">
            <el-image :src="form.logo" class="plugin-logo"></el-image>
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
    <!-- 用户信息 -->
    <div class="user-container">
      <div class="title">用户信息</div>
    </div>
    <el-form :model="user" label-position="top" class="form-container">
      <el-row :gutter="20">
        <el-col :span="12">
          <el-form-item label="账号昵称">
            {{ user.nickname ? user.nickname : "未设置昵称" }}
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="账号余额">
            {{ user.money }}
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
    <div class="text-center mt-3">
      <el-button type="primary" style="width: 100%" @click="onSubmit">
        确认购买
      </el-button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    queryParams: {
      type: Object,
      default: () => {
        return {};
      },
    },
  },
  data() {
    return {
      user: {
        nickname: "",
        money: "0.00",
      },
      form: {
        id: "",
        title: "",
        version: "",
        logo: "",
        platform: "",
        plugin_type: "",
        author: {
          title: "",
        },
      },
    };
  },
  created() {
    this.initify();
  },
  methods: {
    sendBuy() {
      const _this = this;
      const queryParams = {
        id: this.form.id,
      };
      _this.$http.useGet("/admin/Plugin/buy", queryParams).then((res) => {
        _this.$emit("closeWin");
        _this.$useNotify.useNotifySuccess(res?.msg);
      });
    },
    // 提交购买
    onSubmit() {
      const _this = this;
      this.$confirm.useConfirm("是否确认购买该应用插件？", () => {
        _this.sendBuy();
      });
    },
    getUser() {
      const _this = this;
      _this.$http
        .useGet("/admin/Cloud/index")
        .then((res) => {
          const { data } = res;
          _this.user = data;
        })
        .catch((err) => {
          if (err?.code == 12000) {
            _this.openWin("remote/cloud/login");
          }
        });
    },
    getDetail() {
      const _this = this;
      const queryParams = {
        ...this.queryParams,
      };
      this.$http
        .useGet("/admin/Plugin/detail", queryParams)
        .then((res) => {
          _this.form = res?.data ?? {};
        })
        .catch((err) => {
          if (err?.code == 12000) {
            _this.openWin("remote/cloud/login");
          }
        });
    },
    initify() {
      this.getUser();
      this.getDetail();
    },
  },
};
</script>

<style lang="scss" scoped>
.page-container {
  .form-container {
    .plugin-logo {
      width: 40px;
      height: 40px;
      border-radius: 5px;
    }
  }

  .user-container {
    .title {
      font-size: 16px;
      padding: 10px 0;
      font-weight: 700;
      border-top: 1px solid #d1d1d1;
    }
  }
}
</style>
