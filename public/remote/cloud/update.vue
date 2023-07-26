<template>
  <div class="page-container" v-if="initLock">
    <!-- 应用信息 -->
    <n-table size="large" :single-line="false" :bordered="true">
      <tbody>
        <tr class="bg-green-tr">
          <td>应用名称</td>
          <td>{{ form?.title }}</td>
          <td>最新版本</td>
          <td>{{ form?.version_name }}【{{ form?.version }}】</td>
        </tr>
        <tr>
          <td>支持平台</td>
          <td>
            <div class="platform-box">
              <img :src="item?.url" class="logo" v-for="(item, index) in form.platform_icon" :key="index" />
            </div>
          </td>
          <td>本地版本号</td>
          <td>{{ form?.localVersion }}</td>
        </tr>
        <tr class="bg-green-tr">
          <td>开发者</td>
          <td>{{ form?.dev?.title }}</td>
          <td>应用图标</td>
          <td>
            <img :src="form.logo" class="logo" />
          </td>
        </tr>
      </tbody>
    </n-table>
    <div class="submit-button">
      <n-button type="primary" class="button" block @click="onSubmit">
        开始更新
      </n-button>
    </div>
    <div class="app-desc-container">
      <div class="app-desc-title">应用描述</div>
      <n-tag type="primary" class="app-desc">
        {{ form?.desc ? form.desc : "暂无更多描述" }}
      </n-tag>
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
    this.getUser();
  },
  methods: {
    sendBuy() {
      const _this = this;
      const queryParams = {
        ...this.ajaxParams,
      };
      _this.$http.usePost("admin/Plugin/buy", queryParams).then((res) => {
        if (res.code === 200) {
          _this.$emit("update:openWin", "remote/cloud/install");
          _this.$useNotification?.success({
            title: res?.msg ?? "操作成功",
            duration: 1500,
          });
        } else {
          _this.$useNotification?.error({
            title: res?.msg ?? "获取失败",
            duration: 1500,
          });
        }
      });
    },
    // 购买
    onBuy() {
      const _this = this;
      _this.$useDialog.create({
        type: "warning",
        title: "温馨提示",
        content: "是否确认购买该应用？",
        positiveText: "确定",
        negativeText: "取消",
        maskClosable: false,
        onPositiveClick() {
          _this.sendBuy();
        },
      });
    },
    // 提交
    onSubmit() {
      const _this = this;
      _this.$useDialog.create({
        type: "warning",
        title: "温馨提示",
        content: "是否确认开始更新？",
        positiveText: "确定",
        negativeText: "取消",
        maskClosable: false,
        onPositiveClick() {
          _this.openWin("remote/cloud/updateing");
        },
      });
    },
    openWin(path) {
      this.$emit("update:openWin", path);
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
              title: res?.msg ?? "获取失败",
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
          _this.getDetail();
        })
        .catch((err) => {
          if (err?.code == 11000) {
            _this.$emit("openWin", "remote/cloud/login");
          }
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.page-container {
  padding: 20px;
  overflow-y: auto;
  overflow-x: hidden;
  height: 100%;

  .bg-green-tr {
    --n-td-color-modal: rgba(231, 245, 238, 1);
    --n-td-text-color: rgba(24, 160, 88, 1);
  }

  .submit-button {
    margin-top: 30px;

    .button {
      height: 45px;
    }
  }

  .install-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 30px;

    .install-box {
      text-align: center;
    }
  }

  .app-desc-container {
    margin-top: 20px;

    .app-desc-title {
      font-size: 25px;
      font-weight: 700;
      text-align: center;
    }

    .app-desc {
      width: 100%;
      height: 180px;
      display: block;
      padding: 10px;
      margin-top: 10px;
      white-space: pre-line;
    }
  }
}

.platform-box {
  display: flex;
  gap: 12px;
}

.logo {
  width: 36px;
  height: 36px;
}

.money {
  color: red;
}</style>
