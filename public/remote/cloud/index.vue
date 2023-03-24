<template>
  <div class="cloud-container">
    <!-- 用户块 -->
    <div class="user-center" v-if="isLogin">
      <div class="user">
        <!-- 用户 -->
        <div class="user-info">
          <el-avatar :src="user.headimg" />
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
          <el-button type="success" @click="hanldOpenBrowser('user/recharge')">
            <span class="fa fa-credit-card"></span>
            <span class="ml-2">充值</span>
          </el-button>
        </div>
      </div>
    </div>
    <!-- 工具块 -->
    <div class="block-container">
      <div class="title">
        <span class="fa fa-tv"></span>
        <span class="ml-2">工具服务</span>
      </div>
      <el-row :gutter="20">
        <el-col :span="6" class="tool-col">
          <div class="item" @click="hanldOpenBrowser('perfect')">
            <el-image
              src="https://cube.elemecdn.com/3/7c/3ea6beec64369c2642b92c6726f1epng.png"
              class="tool-logo"
            >
            </el-image>
            <div>个人资料</div>
          </div>
        </el-col>
        <el-col :span="6" class="tool-col">
          <div class="item" @click="hanldOpenBrowser('wallet')">
            <el-image
              src="https://cube.elemecdn.com/3/7c/3ea6beec64369c2642b92c6726f1epng.png"
              class="tool-logo"
            >
            </el-image>
            <div>我的钱包</div>
          </div>
        </el-col>
        <el-col :span="6" class="tool-col" @click="hanldOpenBrowser('plugin')">
          <div class="item">
            <el-image
              src="https://cube.elemecdn.com/3/7c/3ea6beec64369c2642b92c6726f1epng.png"
              class="tool-logo"
            >
            </el-image>
            <div>我的应用</div>
          </div>
        </el-col>
        <el-col :span="6" class="tool-col" @click="hanldOpenBrowser('site')">
          <div class="item">
            <el-image
              src="https://cube.elemecdn.com/3/7c/3ea6beec64369c2642b92c6726f1epng.png"
              class="tool-logo"
            >
            </el-image>
            <div>我的站点</div>
          </div>
        </el-col>
      </el-row>
    </div>
    <!-- 账单流水 -->
    <div class="block-container">
      <div class="title">
        <span class="fa fa-file-text-o"></span>
        <span class="ml-2">账单流水</span>
      </div>
      <el-table :data="datalist" stripe border>
        <el-table-column prop="date" label="账单时间" width="180" />
        <el-table-column prop="name" label="账单金额" width="150" />
        <el-table-column prop="address" label="账单备注" />
      </el-table>
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
    // 打开新的浏览器窗口
    hanldOpenBrowser(path) {
      const url = `http://kfadmin.net/user/#/${path}`;
      window.open(url);
    },
    openWin(path) {
      this.$emit("openWin", path);
    },
    init() {
      var _this = this;
      _this.$http
        .useGet("/admin/Cloud/index")
        .then((e) => {
          const { data } = e;
          _this.user = data;
          _this.isLogin = true;
        })
        .catch((err) => {
          if (err?.code == 12000) {
            _this.openWin("remote/cloud/login");
          }
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.cloud-container {
  .user-center {
    .user {
      display: flex;
      justify-content: space-between;
      border-bottom: 1px solid #ddd;
      padding: 10px 0;

      .user-info {
        display: flex;

        .info {
          padding: 3px 0 0 8px;
          font-size: 14px;
        }
      }

      .recharge {
      }
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
