<template>
    <div class="update-container">
        <div class="step-container">
            <n-steps vertical :current="stepData.step" :status="stepData.status">
                <n-step v-for="(item, index) in stepData.list" :key="index" :title="item.title" />
            </n-steps>
        </div>
        <div class="content-container">
            <img src="/image/logo.png" class="logo" alt="">
            <div class="title">KFAdmin</div>
            <div class="loading">
                <vxe-icon name="refresh" class="loading-icon" roll></vxe-icon>
                <div>{{ stepData.stepText ? `正在${stepData.stepText}...` : '出现异常错误' }}</div>
            </div>
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
            stepData: {
                step: 0,
                stepText: '',
                status: 'process',
                list: [
                    {
                        step: 'backcode',
                        title: '备份代码',
                    },
                    // {
                    //     step: 'backsql',
                    //     title: '备份数据库',
                    // },
                    {
                        step: 'download',
                        title: '下载更新包',
                    },
                    {
                        step: 'delplugin',
                        title: '删除旧文件',
                    },
                    {
                        step: 'unzip',
                        title: '解压更新包',
                    },
                    {
                        step: 'reload',
                        title: '重启服务',
                    },
                    {
                        step: 'ping',
                        title: '检测服务状态',
                    },
                    {
                        step: 'updateData',
                        title: '更新数据同步',
                    },
                ],
            }
        }
    },
    created() {
        this.exceStep('backcode');
    },
    methods: {
        exceStep(step) {
            const _this = this;
            const queryParams = {
                ..._this.ajaxParams,
            };
            const item = _this.stepData.list.find(item => item.step === step);
            _this.stepData.stepText = item.title;
            _this.stepData.step = _this.stepData.list.findIndex(item => item.step === step) + 1;
            _this.$http.usePost(`admin/Plugin/update?step=${step}`, queryParams).then((res) => {
                if (res.data.next === 'success') {
                    setTimeout(() => {
                        _this.$emit("update:closeWin");
                    }, 2000);
                    _this.stepData.stepText = res.msg;
                    _this.$useNotification?.success({
                        title: res?.msg ?? "操作成功",
                        duration: 1500,
                    });
                } else {
                    _this.exceStep(res.data.next);
                }
            }).catch((err) => {
                if (err.response.status === 502) {
                    step = 'ping';
                    setTimeout(() => {
                        _this.exceStep(step);
                    }, 1000)
                    return;
                }
                if (step === 'reload') {
                    step = 'ping';
                    setTimeout(() => {
                        _this.exceStep(step);
                    }, 1000)
                } else {
                    console.log('error', err);
                    setTimeout(() => {
                        _this.$emit("update:closeWin");
                    }, 2000);
                    _this.$useNotification?.error({
                        title: res?.msg ?? "获取失败",
                        duration: 1500,
                    });
                }
            })
        }
    },
};
</script>
  
<style lang="scss">
.update-container {
    display: flex;
    width: 100%;
    height: 100%;
    border-top: 1px solid #e5e5e5;
    margin-top: 10px;

    .step-container {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0 15px;
        border-right: 1px solid #e5e5e5;
    }

    .content-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;

        .logo {
            width: 90px;
            height: 90px;
            border-radius: 10px;
        }

        .title {
            font-size: 20px;
            font-weight: 700;
            margin-top: 10px;
        }

        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            gap: 10px;

            .loading-icon {
                font-size: 22px;
            }
        }
    }
}
</style>
  