<template>
  <div class="page-container">
    <!-- 应用信息 -->
    <n-form :model="form" labn-position="top" class="form-container">
      <n-row :gutter="20">
        <n-col :span="12">
          <n-form-item label="应用名称">
            {{ form?.title }}
          </n-form-item>
        </n-col>
        <n-col :span="12">
          <n-form-item label="应用版本">
            {{ form?.version }}
          </n-form-item>
        </n-col>
        <n-col :span="12">
          <n-form-item label="平台类型">
            {{ form?.platform }}
          </n-form-item>
        </n-col>
        <n-col :span="12">
          <n-form-item label="应用类型">
            {{ form?.plugin_type }}
          </n-form-item>
        </n-col>
        <n-col :span="12">
          <n-form-item label="开发者名称">
            {{ form?.dev?.title }}
          </n-form-item>
        </n-col>
        <n-col :span="12">
          <n-form-item label="应用图标">
            <n-image :src="form?.logo" class="plugin-logo"></n-image>
          </n-form-item>
        </n-col>
      </n-row>
    </n-form>
    <!-- 用户信息 -->
    <div class="user-container">
      <div class="title">用户信息</div>
    </div>
    <n-form :model="user" labn-position="top" class="form-container">
      <n-row :gutter="20">
        <n-col :span="12">
          <n-form-item label="账号昵称">
            {{ user.nickname ? user.nickname : "未设置昵称" }}
          </n-form-item>
        </n-col>
        <n-col :span="12">
          <n-form-item label="账号余额">
            {{ user.money }}
          </n-form-item>
        </n-col>
      </n-row>
    </n-form>
    <div class="action-button">
      <n-button type="primary" block @click="onSubmit">
        确认购买
      </n-button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    ajaxParams: {
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
        name:'',
        title: "",
        version: "",
        logo: "",
        platform: "",
        plugin_type: "",
        dev: {
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
        name: this.form.name,
      };
      _this.$http.useGet("admin/Plugin/buy", queryParams).then((res) => {
        const { data } = res;
        if (data.code === 200) {
          _this.$emit("closeWin");
          _this.$useNotification?.success({
            title: data?.msg ?? '操作成功',
            duration: 1500,
          });
        } else {
          _this.$useNotification?.error({
            title: data?.msg ?? '操作失败',
            duration: 1500,
          });
        }
      });
    },
    // 提交购买
    onSubmit() {
      const _this = this;
      _this.$useDialog.create({
        type:'warning',
        title: '温馨提示',
        content: '是否确认购买该应用插件？',
        positiveText:'确定',
        negativeText: '取消',
        maskClosable:false,
        onPositiveClick() {
          _this.sendBuy();
        }
      });
    },
    getUser() {
      const _this = this;
      _this.$http
        .useGet("admin/PluginCloud/index")
        .then((res) => {
          const { data } = res;
          _this.user = data;
        })
        .catch((err) => {
          if (err?.code == 11000) {
            _this.openWin("remote/cloud/login");
          }
        });
    },
    getDetail() {
      const _this = this;
      const queryParams = {
        ...this.ajaxParams,
      };
      this.$http
        .useGet("admin/Plugin/detail", queryParams)
        .then((res) => {
          const { data } = res;
          if (data.code === 200) {
            _this.form = data?.data ?? {};
          } else {
            _this.$useNotification?.error({
              title: data?.msg ?? '出现错误',
              duration: 1500,
            });
          }
        })
        .catch((err) => {
          if (err?.code == 11000) {
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
  padding: 20px;

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

  .action-button {
    margin-top: 10px;
  }
}
</style>
