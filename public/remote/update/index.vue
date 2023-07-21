<template>
  <div class="update-container">
    <div class="logo-container">
      <img class="logo" src="/image/logo.png" />
      <div class="logo-title">KFAdmin</div>
      <div class="version">
        当前版本：{{ updated.client_version_name }} （最新版：{{ updated.version_name }}）
      </div>
    </div>
    <div class="update-box" v-if="!install.status">
      <n-form
        labn-position="left"
        class="form-container"
        v-if="updated.version > updated.client_version"
      >
        <n-form-item :label="`${updated.title}，客户端版本号：${updated.client_version}，最新：${updated.version}`">
          <n-input
            type="textarea"
            :value="updated.content"
            :autosize="{ minRows: 6, maxRows: 10 }"
            placeholder="无"
            disabled
          />
        </n-form-item>
        <n-form-item label="更新文件">
          <n-input
            type="textarea"
            :value="updated.chanage_files"
            :autosize="{ minRows: 6, maxRows: 10 }"
            placeholder="无"
            disabled
          />
        </n-form-item>
        <div class="action-button">
          <n-button type="success" @click="hanldUpdate">立即更新</n-button>
          <n-button type="warning" @click="hanldCancel">忽略本次更新</n-button>
        </div>
      </n-form>
      <div class="empty-container" v-else>
        <n-empty :show-description="false">
          <template #icon>
            <AppIcons icon="CheckCircleOutlined" :size="48" color="#18a058" />
          </template>
          <template #extra="">
            <div class="mt-5">当前已经是最新版</div>
          </template>
        </n-empty>
      </div>
    </div>
    <!-- 更新状态 -->
    <div class="loading-container" v-else>
      <div class="loading-box">
        <n-progress type="circle" :percentage="install.progress" />
        <div>{{ install.text }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      install: {
        status: false,
        progress: 0,
        text:''
      },
      updated: {
        title: "",
        version_name: "",
        version: "",
        client_version_name: "",
        client_version: "",
        content: "",
      },
    };
  },
  mounted() {
    this.initify();
  },
  methods: {
    // 提交更新
    hanldUpdate() {
      const _this = this;
      _this.$useDialog.create({
        type: "warning",
        title: "温馨提示",
        content: "是否确定更新框架系统？请自行备份数据库与代码文件",
        positiveText: "确定",
        negativeText: "取消",
        maskClosable: false,
        onPositiveClick() {
          const data = {
            version: _this.updated.version,
          };
          _this.install.status = true;
          _this.install.text = '正在安装中...';
          const setIntervalObj = setInterval(() => {
            if (_this.install.progress < 100) {
              const progress = Math.random();
              const progress_num = _this.install.progress + progress;
              _this.install.progress = parseFloat(progress_num.toFixed(2));
            }
          }, 300);
          _this.$http
            .usePost("admin/Updated/updateCheck", data)
            .then((res) => {
              _this.install.text = '等待服务重启...';
              _this.install.progress = 100;
              clearInterval(setIntervalObj);
              setTimeout(() => {
                window.location.reload();
              }, 5000);
              _this.$useNotification?.success({
                title: res?.msg ?? "操作成功",
                duration: 1500,
              });
            })
            .catch((err) => {
              clearInterval(setIntervalObj);
              _this.install.status = false;
              _this.install.progress = 0;
              _this.$useNotification?.error({
                title: err?.msg ?? "更新出错",
                duration: 1500,
              });
            });
        },
      });
    },
    // 取消本次更新
    hanldCancel() {
      const _this = this;
      _this.$useDialog.create({
        type: "warning",
        title: "温馨提示",
        content: "是否确认忽略本次版本更新？下个版本依然继续提示更新",
        positiveText: "确定",
        negativeText: "取消",
        maskClosable: false,
        onPositiveClick() {
          localStorage.setItem("system_updated", _this.updated.version);
          window.location.reload();
        },
      });
    },
    initify() {
      const _this = this;
      _this.$http.usePut("admin/Updated/updateCheck").then((res) => {
        const { data } = res;
        _this.updated = data;
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.update-container {
  background: #fff;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  overflow-y: auto;
  overflow-x: hidden;
  .logo-container {
    text-align: center;
    .logo {
      width: 80px;
      height: 80px;
      margin: 0 auto;
      border-radius: 10px;
    }
    .logo-title {
      font-size: 20px;
      font-weight: 700;
      margin-top: 5px;
    }
  }
  .update-box {
    width: 60%;
    .empty-container {
      margin-top: 100px;
    }

    .title {
      text-align: center;
      font-size: 18px;
      font-weight: 700;
      margin-top: 20px;
    }

    .form-container {
      margin-top: 20px;

      .action-button {
        display: flex;
        gap: 80px;
        align-items: center;
        justify-content: center;
      }
    }
  }
  .loading-container {
    margin-top: 30px;
    .loading-box {
      text-align: center;
    }
  }
}
</style>
