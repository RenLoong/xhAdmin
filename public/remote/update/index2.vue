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
        <div class="version-box">
          <div class="title">版本更新内容</div>
          <div class="version-content">
            当前版本更新内容
          </div>
        </div>
        <div class="action-button">
          <n-button type="success" @click="hanldUpdate">立即更新</n-button>
          <!-- <n-button type="warning" @click="hanldCancel">忽略本次更新</n-button> -->
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
        step: 1,
        list: [
          {
            title: '下载更新包',
          },
          {
            title: '备份代码',
          },
          {
            title: '备份数据库',
          },
          {
            title: '删除旧文件',
          },
          {
            title: '解压更新包',
          },
          {
            title: '更新数据同步',
          },
        ],
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
  methods: {
    hanldUpdate() {

    }
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
        }
      }

      .action-button {
        display: flex;
        gap: 80px;
        align-items: center;
        justify-content: center;
        margin-top: 20px;
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
</style>
