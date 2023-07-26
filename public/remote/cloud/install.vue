<template>
  <div class="page-container" v-if="initLock">
    <!-- 应用信息 -->
    <n-table size="large" :single-line="false" :bordered="true">
      <tbody>
        <tr class="bg-green-tr">
          <td>应用名称</td>
          <td>{{ findData?.title }}</td>
          <td>应用版本</td>
          <td>{{ findData?.version_name }}</td>
        </tr>
        <tr>
          <td>支持平台</td>
          <td>
            <div class="platform-box">
              <img :src="item?.url" class="logo" v-for="(item, index) in findData.platform_icon" :key="index" />
            </div>
          </td>
          <td>应用类型</td>
          <td>{{ findData?.plugin_type_text }}</td>
        </tr>
        <tr class="bg-green-tr">
          <td>开发者</td>
          <td>{{ findData?.dev?.title }}</td>
          <td>应用图标</td>
          <td>
            <img :src="findData.logo" class="logo" />
          </td>
        </tr>
        <tr>
          <td>应用售价</td>
          <td>
            <span class="money" v-if="discount > 0 && discount < 1">￥{{ (findData.money * discount).toFixed(2) }}</span>
            <span class="money" v-else>￥{{ findData.money ?? 0 }}</span>
          </td>
          <td>云平台余额</td>
          <td>
            <span class="money">￥{{ user.money ?? 0 }}</span>
          </td>
        </tr>
      </tbody>
    </n-table>
    <div class="install-container" v-if="installLock.status">
      <div class="install-box">
        <n-progress type="circle" :percentage="installLock.progress" />
        <div>正在安装中...</div>
      </div>
    </div>
    <div class="submit-button" v-else>
      <n-button type="warning" class="button" block @click="onSubmit" v-if="findData?.is_buy">
        开始安装
      </n-button>
      <div v-else>
        <div class="flex mb-4">
          <div class="flex-1" v-if="Coupon.length > 0">
            <vxe-select v-model="form.coupon_code" placeholder="选择优惠券" class="coupon-select" @change="CouponSelected">
              <vxe-option v-for="(item, index) in Coupon" :key="index" :value="item.coupon_code" :label="item.title">
                <div class="flex">
                  <div class="flex-1">{{ item.title }}</div>
                  <div>{{ item.discount * 10 }}折</div>
                </div>
              </vxe-option>
            </vxe-select>
          </div>
          <n-button type="primary" class="button flex-2" block @click="onBuy">
            确认购买
          </n-button>
        </div>
        <n-alert title="购买须知" type="warning">
          购买后将绑定当前站点：{{ host }}，将仅限当前站点安装、更新、卸载
        </n-alert>
      </div>
    </div>
    <div class="app-desc-container">
      <div class="app-desc-title">应用描述</div>
      <n-tag type="primary" class="app-desc">
        {{ findData?.desc ? findData.desc : "暂无更多描述" }}
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
      // 是否在安装中
      installLock: {
        status: false,
        progress: 0,
      },
      host: window.location.host,
      user: {
        money: 0
      },
      discount: 0,
      Coupon: [],
      form: {
        name: '',
        version: '',
        coupon_code: '',
      },
      findData: {
        id: "",
        title: "",
        version: "",
        logo: "",
        platform: "",
        plugin_type: "",
        coupon_code: "",
        money: 0,
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
    // 发送购买请求
    sendBuy() {
      const _this = this;
      _this.$http.usePost("admin/Plugin/buy", this.form).then((res) => {
        if (res.code === 200) {
          _this.$emit("update:openWin", "remote/cloud/install");
          _this.$useNotification?.success({
            title: "操作成功",
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
        content: "是否确认开始安装？",
        positiveText: "确定",
        negativeText: "取消",
        maskClosable: false,
        onPositiveClick() {
          _this.openWin("remote/cloud/installing");
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
            this.findData = res?.data;
            this.form.name = res?.data?.name;
            this.form.version = res?.data?.version;
            this.getAvailableCoupon(res?.data?.id);
            _this.initLock = true;
          } else {
            _this.$useNotification?.error({
              title: res?.msg ?? "获取失败",
              duration: 1500,
              onAfterLeave: () => {
                _this.$emit("update:closeWin");
              }
            });
          }
        })
        .catch((err) => {
          if (err?.code == 11000) {
            _this.openWin("remote/cloud/login");
          }
        });
    },
    getAvailableCoupon(id) {
      this.$http
        .useGet("admin/PluginCloud/getAvailableCoupon", { type: 'apps', plugin_id: id })
        .then((res) => {
          if (res.code === 200) {
            this.Coupon = res?.data;
            if (this.Coupon.length > 0) {
              this.CouponSelected({ value: this.Coupon[0].coupon_code });
            }
          }
        });
    },
    CouponSelected({ value: coupon_code }) {
      this.form.coupon_code = coupon_code;
      const find = this.Coupon.find((item) => item.coupon_code == coupon_code);
      if (find) {
        this.discount = find.discount;
      }
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
}

.mb-4 {
  margin-bottom: 10px;
}

.flex {
  display: flex;
  align-items: center;
}

.flex-1 {
  flex: 1;
}

.flex-2 {
  flex: 2;
}

.coupon-select {
  width: 95%;

  .vxe-input {
    height: 45px;
    line-height: 45px;
  }
}
</style>