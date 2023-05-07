<template>
  <div class="cloud-container">
    <!-- 用户块 -->
    <div class="user-center" v-if="isLogin">
      <div class="user">
        <!-- 用户 -->
        <div class="user-info">
          <n-avatar :src="user.headimg" size="large" />
          <div class="info">
            <div class="nickname">
              <span class="fa fa-user-o"></span>
              {{ user.nickname ? user.nickname : "未设置昵称" }}
            </div>
            <div class="money">
              <span class="fa fa-cny"></span>
              {{ user.money ? user.money : "0.00" }}
            </div>
          </div>
        </div>
        <!-- 充值 -->
        <div class="recharge">
          <n-button type="success" @click="hanldOpenBrowser('user/recharge')">
            <template #icon>
              <app-icons icon="CreditCardOutlined" />
            </template>
            <span>充值</span>
          </n-button>
        </div>
      </div>
    </div>
    <!-- 工具块 -->
    <div class="block-container">
      <div class="title">
        <span class="fa fa-tv"></span>
        <span class="ml-2">工具服务</span>
      </div>
      <n-grid :cols="4">
        <n-grid-item class="tool-col">
          <div class="item" @click="hanldOpenBrowser('perfect')">
            <n-image src="https://cube.elemecdn.com/3/7c/3ea6beec64369c2642b92c6726f1epng.png" class="tool-logo" />
            <div>个人资料</div>
          </div>
        </n-grid-item>
        <n-grid-item class="tool-col">
          <div class="item" @click="hanldOpenBrowser('wallet')">
            <n-image src="https://cube.elemecdn.com/3/7c/3ea6beec64369c2642b92c6726f1epng.png" class="tool-logo" />
            <div>我的钱包</div>
          </div>
        </n-grid-item>
        <n-grid-item class="tool-col">
          <div class="item" @click="hanldOpenBrowser('plugin')">
            <n-image src="https://cube.elemecdn.com/3/7c/3ea6beec64369c2642b92c6726f1epng.png" class="tool-logo" />
            <div>我的应用</div>
          </div>
        </n-grid-item>
        <n-grid-item class="tool-col">
          <div class="item" @click="hanldOpenBrowser('site')">
            <n-image src="https://cube.elemecdn.com/3/7c/3ea6beec64369c2642b92c6726f1epng.png" class="tool-logo" />
            <div>我的站点</div>
          </div>
        </n-grid-item>
      </n-grid>
    </div>
    <!-- 账单流水 -->
    <div class="block-container">
      <div class="title">
        <span class="fa fa-file-text-o"></span>
        <span class="ml-2">账单流水</span>
      </div>
      <!-- <el-table :data="datalist" stripe border>
        <el-table-column prop="date" label="账单时间" width="180" />
        <el-table-column prop="name" label="账单金额" width="150" />
        <el-table-column prop="address" label="账单备注" />
      </el-table> -->
    </div>
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
      datalist: [],
    };
  },
  props: {
    url: String,
  },
  created() {
    this.init();
  },
  methods: {
    /**
     * 打开新的浏览器窗口
     * @param {*} path 
     */
    hanldOpenBrowser(path) {
      const url = `http://kfadmin.net/user/#/${path}`;
      window.open(url);
    },
    /**
     * 打开新页面
     * @param {*} path 
     */
    openWin(path) {
      this.$emit("openWin", path);
    },
    // 初始化业务
    init() {
      var _this = this;
      _this.$http
        .useGet("/admin/PluginCloud/index")
        .then((e) => {
          const { data } = e;
          _this.user = data;
          _this.isLogin = true;
        })
        .catch((err) => {
          if (err?.code == 11000) {
            _this.$emit('openWin', 'remote/cloud/login')
          }
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.cloud-container {
  padding: 20px;

  .user-center {
    .user {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #ddd;
      padding: 10px 0;

      .user-info {
        display: flex;
        align-items: center;

        .info {
          padding: 3px 0 0 8px;
          font-size: 14px;
        }
      }

      .recharge {}
    }
  }

  .block-container {
    margin-top: 20px;

    .title {
      font-size: 16px;
      font-weight: 700;
      padding: 10px 0;
      user-select: none;
    }

    .tool-col {
      text-align: center;

      .item {
        cursor: pointer;
        display: inline-block;
        background: #fff;
        padding: 10px 15px;
        border-radius: 3px;

        .tool-logo {
          width: 40px;
          height: 40px;
        }
      }

      .item:hover {
        background: #f1f1f1;
      }
    }
  }
}
</style>
