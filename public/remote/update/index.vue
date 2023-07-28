<template>
  <div class="update-container">
    <!-- 更新版本 -->
    <div class="update-box" v-if="updated.update">
      <div class="step-container">
        <n-steps vertical :current="stepData.step" :status="stepData.status">
          <n-step v-for="(item, index) in stepData.list" :key="index" :title="item.title" />
        </n-steps>
      </div>
      <div class="content-box">
        <div class="logo-container">
          <img class="logo" src="/image/logo.png" />
          <div class="logo-title">KFAdmin</div>
          <div class="version">
            当前版本：{{ updated.client_version_name }} （最新版：{{ updated.version_name }}）
          </div>
        </div>
        <!-- 版本信息展示 -->
        <div class="updated-content" v-if="stepData.step <= 0">
          <div class="version-box">
            <div class="title">版本更新内容</div>
            <div class="version-content">
              <pre class="version-pre">{{ updated?.content }}</pre>
            </div>
          </div>
          <div class="action-button" v-if="updated?.version_name">
            <n-button type="success" @click="hanldUpdate">立即更新</n-button>
            <n-button type="warning" v-if="install.ignore" @click="hanldCancel">
              忽略本次更新
            </n-button>
          </div>
        </div>
        <!-- 更新中 -->
        <div class="updated-ing" v-else>
          <div class="loading">
            <vxe-icon name="refresh" class="loading-icon" roll></vxe-icon>
            <div>{{ stepData.stepText ? stepData.stepText : '出现异常错误' }}</div>
            <div class="text-red">更新过程中，请勿刷新页面或离开当前页面...</div>
          </div>
        </div>
      </div>
    </div>
    <!-- 无更新 -->
    <div class="update-empty" v-else>
      <div class="logo-container">
        <img class="logo" src="/image/logo.png" />
        <div class="logo-title">KFAdmin</div>
        <div class="version">
          当前版本：{{ updated.client_version_name }} （最新版：{{ updated.version_name }}）
        </div>
      </div>
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
</template>

<script>
export default {
  data() {
    return {
      stepData: {
        status: 'process',
        step: 0,
        stepText: '',
        list: [
          {
            title: '下载更新包',
            step: 'download',
          },
          {
            title: '备份代码',
            step: 'backCode',
          },
          {
            title: '备份数据库',
            step: 'backSql',
          },
          {
            title: '解压更新包',
            step: 'unzip',
          },
          {
            title: '更新数据',
            step: 'updateData',
          },
          {
            title: '重启服务',
            step: 'reload',
          },
          {
            title: '等待重启',
            step: 'ping',
          },
          {
            title: '更新成功',
            step: 'success',
          },
        ],
      },
      install: {
        // 是否更新
        status: true,
        // 是否显示忽略更新按钮
        ignore: false,
      },
      updated: {
        update: true,
        title: "",
        version_name: "",
        version: "",
        client_version_name: "",
        client_version: "",
        content: "",
      },
    };
  },
  created() {
    this.getDetail();
  },
  methods: {
    hanldStepUpdate(step) {
      const _this = this;
      const version = _this.updated.version;
      // 设置开始更新与文字
      const item = _this.stepData.list.find(item => item.step === step);
      _this.stepData.stepText = `正在${item.title}...`;
      // 设置当前步骤
      _this.stepData.step = _this.stepData.list.findIndex(item => item.step === step) + 1;
      // 发送更新请求
      _this.$http.usePost(`admin/Updated/updateCheck?step=${step}&version=${version}`).then((res) => {
        if (res.data.next === 'success') {
          _this.stepData.step = _this.stepData.list.findIndex(item => item.step === res.data.next) + 1;
          _this.stepData.stepText = res.msg;
          setTimeout(() => {
            _this.$router.push({ path: '/Index/index' });
          }, 1500);
        } else {
          setTimeout(() => {
            _this.hanldStepUpdate(res.data.next);
          }, 500);
        }
      }).catch((err) => {
        if (err.response.status === 502) {
          step = 'ping';
          setTimeout(() => {
            _this.hanldStepUpdate(step);
          }, 1000)
          return;
        }
        if (step === 'reload') {
          step = 'ping';
          setTimeout(() => {
            _this.hanldStepUpdate(step);
          }, 1000)
        } else {
          console.log('error', err);
          _this.$useNotification?.error({
            title: res?.msg ?? "获取失败",
            duration: 1500,
          });
        }
      });
    },
    hanldUpdate() {
      const _this = this;
      _this.$useDialog.create({
        type: "warning",
        title: "温馨提示",
        content: "是否确定现在开始更新系统框架？",
        positiveText: "确定",
        negativeText: "取消",
        maskClosable: false,
        onPositiveClick() {
          _this.hanldStepUpdate('download');
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
    getDetail() {
      const _this = this;
      _this.$http
        .usePut("admin/Updated/updateCheck")
        .then((res) => {
          const { data } = res;
          data.update = data.version > data.client_version;
          _this.updated = data;
          // 是否忽略更新
          const ignore = parseInt(localStorage.getItem("system_updated"));
          if (ignore !== this.updated.version) {
            this.install.ignore = true;
          }
        })
    },
  },
};
</script>

<style lang="scss">
.update-container {
  background: #fff;
  width: 100%;
  height: 100%;

  .update-box {
    display: flex;
    height: 100%;
    padding: 15px;

    .step-container {
      display: flex;
      justify-content: center;
      align-items: center;
      border-right: 1px solid #e5e5e5;
      padding: 0 30px;
    }

    .content-box {
      flex: 1;
      padding: 0 30px;

      .updated-content {

        .version-box {
          border-top: 1px solid #e5e5e5;
          margin-top: 20px;

          .title {
            font-size: 20px;
            font-weight: 700;
            padding: 10px 0;
          }

          .version-content {
            height: 350px;
            background: #f5f5f5;
            border: 1px solid #e5e5e5;
            padding: 5px;

            .version-pre {
              font-size: 13px;
              color: #333;
            }
          }
        }

        .action-button {
          display: flex;
          gap: 30px;
          align-items: center;
          justify-content: center;
          margin-top: 20px;
        }
      }

      .updated-ing {
        height: 550px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-top: 1px solid #e5e5e5;
        margin-top: 20px;

        .loading {
          display: flex;
          justify-content: center;
          align-items: center;
          flex-direction: column;
          gap: 10px;

          .loading-icon {
            font-size: 22px;
            margin-bottom: 10px;
          }
        }
      }
    }
  }

  .update-empty {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    gap: 30px;
    justify-content: center;
    align-items: center;
  }
}

.logo-container {
  text-align: center;
  margin-top: 30px;

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

.mt-5 {
  margin-top: 5px;
}

.text-red {
  color: red;
}
</style>
