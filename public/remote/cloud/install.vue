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
    <!-- 安装信息展示 -->
    <div class="progress-container" v-if="install.lock">
      <el-progress type="circle" :status="install.status" :percentage="install.progress">
        <template #default="{ percentage }">
          <div class="percentage-icon fa fa-cube" v-if="install.step == 'success'"></div>
          <div class="percentage-label">{{ install.text }}</div>
          <div class="percentage-value" v-if="percentage">{{ percentage }} %</div>
        </template>
      </el-progress>
    </div>
    <div class="text-center mt-3" v-else>
      <el-button type="primary" style="width: 100%" @click="onSubmit">
        开始安装
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
      // 安装按钮锁定
      install: {
        lock: false,
        step: "",
        text: "",
        status: "",
        progress: 0,
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
    // 执行安装步骤
    installStep() {
      const _this = this;
      // 开发测试
      const host = `//1.116.41.3:39700/`;
      // 生产环境
      // const host = `//${window.location.host}/`;
      const url = `${host}?step=${this.install.step}&id=${this.form.id}`;
      const eventSource = new EventSource(url);
      // 服务器通讯成功
      eventSource.addEventListener("open", (e) => {
        // console.log("发送成功", e);
      });
      // 监听步骤数据
      eventSource.addEventListener("pageinfo", (e) => {
        const { data } = e;
        const response = JSON.parse(data);
        _this.install.text = response.text;
        _this.install.step = response.step;
        _this.install.status = response.status;
        _this.install.progress = 0;
      });
      // 监听进度事件
      eventSource.addEventListener("progress", (e) => {
        const { data } = e;
        const response = JSON.parse(data);
        _this.install.progress = response.progress;

        // 继续
        if (response.next && response.progress == 100) {
          _this.install.step = response.next;
          setTimeout(() => {
            _this.installStep();
          }, 2000);
        }
        // 安装完成
        if (response.next == "" && response.progress == 100) {
          _this.install.text = "安装完成";
          _this.install.step = "success";
          _this.install.progress = 0;
        }
      });
      // 监听出现错误
      eventSource.addEventListener("error", (e) => {
        const { data } = e;
        const response = JSON.parse(data);
        _this.install.text = response.msg;
        _this.install.step = "";
        _this.install.progress = 0;
      });
    },
    // 提交购买
    onSubmit() {
      const _this = this;
      _this.$confirm.useConfirm("是否确认开始安装？", () => {
        _this.install.lock = true;
        _this.install.step = "请求中...";
        _this.install.step = "file";
        _this.installStep();
      });
    },
    getDetail() {
      const _this = this;
      const queryParams = {
        ...this.queryParams,
      };
      _this.$http
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
