<template>
  <div class="page-container" v-if="initLock">
    <!-- 应用信息 -->
    <n-form :model="form" labn-position="top" class="form-container">
      <n-row :cols="20">
        <n-col :span="12">
          <n-form-item label="应用名称">
            {{ form?.title }}
          </n-form-item>
        </n-col>
        <n-col :span="12">
          <n-form-item label="应用版本">
            {{ form?.version_name }}
          </n-form-item>
        </n-col>
        <n-col :span="12">
          <n-form-item label="平台类型">
            {{ form?.platform_text }}
          </n-form-item>
        </n-col>
        <n-col :span="12">
          <n-form-item label="应用类型">
            {{ form?.plugin_type_text }}
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
        <n-col :span="12" v-if="!form?.order?.order_no">
          <n-form-item label="应用价格">
            ￥{{ form.money }}
          </n-form-item>
        </n-col>
        <n-col :span="12" v-if="!form?.order?.order_no">
          <n-form-item label="账户余额">
            <span class="money-item">￥{{ user.money }}</span>
            <span class="money-item">
              <a href="http://www.baidu.com" target="_blank">去充值</a>
            </span>
          </n-form-item>
        </n-col>
        <n-col :span="24">
          <div>
            <n-button type="primary" block @click="onSubmit" v-if="form?.order?.order_no">
              开始安装
            </n-button>
            <n-button type="warning" block @click="onBuy" v-else>
              确认购买
            </n-button>
          </div>
        </n-col>
        <n-col :span="24" v-if="form?.remarks">
          <div class="version-info">
            <div class="version-title">应用描述</div>
            <n-tag type="warning" class="version-remarks"> {{ form?.remarks }} </n-tag>
          </div>
        </n-col>
      </n-row>
    </n-form>
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
      // 页面初始化
      initLock: false,
      user: {},
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
    // 执行安装步骤
    install() {
      const _this = this;
      const queryParams = {
        ...this.ajaxParams,
      };
      _this.$http
        .usePost("admin/Plugin/install", queryParams)
        .then((res) => {
          if (res.code === 200) {
            _this.$emit('update:closeWin');
            _this.$useNotification?.success({
              title: res?.msg ?? '操作成功',
              duration: 1500,
            });
          } else {
            _this.$useNotification?.error({
              title: res?.msg ?? '获取失败',
              duration: 1500,
            });
          }
        })
    },
    sendBuy() {
      const _this = this;
      const queryParams = {
        ...this.ajaxParams,
      };
      _this.$http
        .usePost("admin/Plugin/buy", queryParams)
        .then((res) => {
          if (res.code === 200) {
            _this.$emit('update:openWin', 'remote/cloud/install');
            _this.$useNotification?.success({
              title: res?.msg ?? '操作成功',
              duration: 1500,
            });
          } else {
            _this.$useNotification?.error({
              title: res?.msg ?? '获取失败',
              duration: 1500,
            });
          }
        })
    },
    // 购买
    onBuy() {
      const _this = this;
      _this.$useDialog.create({
        type: 'warning',
        title: '温馨提示',
        content: '是否确认购买该应用？',
        positiveText: '确定',
        negativeText: '取消',
        maskClosable: false,
        onPositiveClick() {
          _this.sendBuy();
        }
      });
    },
    // 提交
    onSubmit() {
      const _this = this;
      _this.$useDialog.create({
        type: 'warning',
        title: '温馨提示',
        content: '是否确认开始安装？',
        positiveText: '确定',
        negativeText: '取消',
        maskClosable: false,
        onPositiveClick() {
          _this.install();
        }
      });
    },
    getDetail() {
      const _this = this;
      const queryParams = {
        ...this.ajaxParams,
      };
      _this.$http
        .useGet("admin/Plugin/detail", queryParams)
        .then((res) => {
          if (res.code === 200) {
            _this.form = res?.data ?? {};
            _this.initLock = true;
          } else {
            _this.$useNotification?.error({
              title: res?.msg ?? '获取失败',
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
    getUser() {
      var _this = this;
      _this.$http
        .useGet("/admin/PluginCloud/index")
        .then((e) => {
          const { data } = e;
          _this.user = data;
        })
        .catch((err) => {
          if (err?.code == 11000) {
            _this.$emit('openWin', 'remote/cloud/login')
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

    .money-item {
      padding-right: 10px;

      a {
        color: #f60;
      }

      a:hover {
        color: rgb(246, 118, 32);
      }
    }

    .version-info {
      margin-top: 5px;

      .version-title {
        padding: 10px 0;
      }

      .version-remarks {
        width: 100%;
      }
    }
  }

  .progress-container {
    text-align: center;
    margin-top: 30px;
    user-select: none;

    .percentage-icon {
      font-size: 22px;
      margin-bottom: 10px;
    }

    .percentage-label {
      margin-top: 5px;
    }

    .percentage-value {
      margin-top: 10px;
    }
  }
}
</style>
