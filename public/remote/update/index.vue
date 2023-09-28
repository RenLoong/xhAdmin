<template>
  <div class="update-container">
    <!-- 更新版本 -->
    <div class="update-box" v-if="updated.update && updated.version">
      <el-steps class="step-container" direction="vertical" :active="stepData.step" process-status="success">
        <el-step v-for="(item, index) in stepData.list" :key="index" :title="item.title" />
      </el-steps>
      <div class="content-box">
        <div class="logo-container">
          <img class="logo" src="/image/logo.png" />
          <div class="logo-title">XHAdmin</div>
          <div class="version">
            <div>当前版本：{{ updated.client_version_name }} ({{ updated.client_version }})</div>
          </div>
        </div>
        <!-- 版本信息展示 -->
        <div class="updated-content" v-if="!stepData.lock">
          <div class="version-box">
            <div class="title">
              <div class="version-title">版本更新内容</div>
              <div class="version-new">{{ updated.version_name }} ({{ updated.version }})</div>
            </div>
            <div class="version-content">
              <pre class="version-pre">{{ updated?.content }}</pre>
            </div>
          </div>
          <div class="action-button" v-if="updated?.version">
            <el-button type="success" class="xh-button" @click="hanldUpdate">
              立即更新
            </el-button>
            <el-button type="warning" class="xh-button" v-if="install.ignore" @click="hanldCancel">
              忽略本次更新
            </el-button>
          </div>
          <div class="update-tip">
            <el-alert title="更新小提示" type="error" :closable="false">
              <div class="update-content">
                <div>1、无论更新成功与否，都会在更新前备份代码与数据库，可放心更新</div>
                <div>2、备份的代码与数据库会在站点根目录下的backup目录下</div>
                <div>3、切记，backup目录下的备份文件，相同版本备份文件会覆盖</div>
                <div>4、更新过程中，会出现更新中的提示，不要刷新页面或离开当前页面</div>
                <div>5、更新前，请确保站点根目录下的.env文件里的APP_DEBUG为false</div>
              </div>
            </el-alert>
          </div>
        </div>
        <!-- 更新中 -->
        <div class="updated-ing" v-else>
          <div class="loading">
            <vxe-icon name="refresh" class="loading-icon" roll></vxe-icon>
            <div>{{ stepData.stepText ? stepData.stepText : '出现异常错误' }}</div>
            <div class="text-red">请勿刷新或离开当前页面...</div>
          </div>
        </div>
      </div>
    </div>
    <!-- 无更新 -->
    <div class="update-empty" v-else>
      <div class="logo-container">
        <img class="logo" src="/image/logo.png" />
        <div class="logo-title">XHAdmin</div>
        <div class="version">
          当前版本：{{ updated.client_version_name }}({{ updated.client_version }})
        </div>
      </div>
      <!-- 版本信息展示 -->
      <div class="updated-content" v-if="!stepData.lock">
        <div class="version-box">
          <div class="title">
            <div class="version-title">当前版本更新内容</div>
            <div class="version-new">
              {{ updated.version_name }} ({{ updated.version }})
              <div class="version-new-tip">当前版本已是最新版</div>
            </div>
          </div>
          <div class="version-content">
            <pre class="version-pre">{{ updated?.content }}</pre>
          </div>
        </div>
        <div class="update-tip">
          <el-alert title="更新小提示" type="error" :closable="false">
            <div class="update-content">
              <div>1、无论更新成功与否，都会在更新前备份代码与数据库，可放心更新</div>
              <div>2、备份的代码与数据库会在站点根目录下的backup目录下</div>
              <div>3、切记，backup目录下的备份文件，相同版本备份文件会覆盖</div>
              <div>4、更新过程中，会出现更新中的提示，不要刷新页面或离开当前页面</div>
              <div>5、更新前，请确保站点根目录下的.env文件里的APP_DEBUG为false</div>
            </div>
          </el-alert>
        </div>
      </div>
      <!-- <el-empty description="当前已经是最新版" /> -->
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
        lock: false,
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
      _this.stepData.step = _this.stepData.list.findIndex(item => item.step === step);
      // 发送更新请求
      _this.$http.usePost(`admin/Updated/updateCheck?step=${step}&version=${version}`).then((res) => {
        if (res?.data?.next === '') {
          _this.stepData.step = _this.stepData.list.findIndex(item => item.step === 'success');
          _this.stepData.stepText = res.msg;
          setTimeout(() => {
            _this.$routerApp.push({ path: '/' });
          }, 2500);
        } else if (res?.data?.next) {
          _this.hanldStepUpdate(res.data.next);
        }
      }).catch((err) => {
        _this.stepData.lock = false;
        if (err?.message?.includes('timeout')) {
          _this.$useNotify("更新失败，网络超时", 'error', '温馨提示', {
            'onClose': () => {
              window.location.reload();
            }
          })
          return;
        }
      });
    },
    hanldUpdate() {
      const _this = this;
      _this.$useConfirm('是否确定现在开始更新系统框架？', '温馨提示', 'success').then(() => {
        _this.stepData.lock = true;
        _this.hanldStepUpdate('download');
      })
    },
    // 取消本次更新
    hanldCancel() {
      const _this = this;
      _this.$useConfirm('确认忽略版本更新？下个版本依然继续提示更新', '温馨提示', 'success').then(() => {
        localStorage.setItem("system_updated", _this.updated.version);
        window.location.reload();
      })
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
        }).catch((err) => {
          if ([600, 666].includes(err?.code)) {
            _this.updated.client_version = err?.data?.client_version
            _this.updated.client_version_name = err?.data?.client_version_name
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
  overflow: hidden;

  .update-box {
    display: flex;
    height: 100%;

    .step-container {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      border-right: 1px solid #e5e5e5;
      padding: 20px 30px;
      box-sizing: border-box;
    }

    .content-box {
      flex: 1;
      padding: 0 30px;

      .updated-content {

        .version-box {
          border-top: 1px solid #e5e5e5;
          margin-top: 20px;

          .title {
            padding: 10px 0;
            display: flex;
            align-items: center;

            .version-title {
              font-weight: 700;
              font-size: 20px;
            }

            .version-new {
              padding-left: 15px;
              display: flex;
              align-items: center;
              justify-content: center;
              color: #ff5900;
              font-size: 18px;
            }
          }

          .version-content {
            height: 250px;
            background: #f9f9f9;
            border: 1px solid #e5e5e5;
            padding: 5px;
            overflow-y: auto;
            overflow-x: hidden;

            .version-pre {
              display: block;
              width: 100%;
              font-size: 13px;
              color: #555;
              white-space: pre-wrap;
              word-break: break-word;
            }
          }
        }

        .action-button {
          display: flex;
          gap: 30px;
          align-items: center;
          justify-content: center;
          margin-top: 20px;

          .xh-button {
            width: 150px;
            height: 35px;
          }
        }

        .update-tip {
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
    justify-content: center;
    align-items: center;

    .updated-content {
      width: 750px;

      .version-box {
        border-top: 1px solid #e5e5e5;
        margin-top: 20px;

        .title {
          padding: 10px 0;
          display: flex;
          align-items: center;

          .version-title {
            font-weight: 700;
            font-size: 20px;
          }

          .version-new {
            padding-left: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ff5900;
            font-size: 18px;
            .version-new-tip {
              font-size: 14px;
              padding-left: 10px;
            }
          }
        }

        .version-content {
          height: 250px;
          background: #f9f9f9;
          border: 1px solid #e5e5e5;
          padding: 5px;
          overflow-y: auto;
          overflow-x: hidden;

          .version-pre {
            display: block;
            width: 100%;
            font-size: 13px;
            color: #555;
            white-space: pre-wrap;
            word-break: break-word;
          }
        }
      }

      .update-tip {
        margin-top: 20px;
      }
    }
  }
}

.update-content {
  line-height: 26px;
}

.logo-container {
  text-align: center;
  margin-top: 20px;

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
