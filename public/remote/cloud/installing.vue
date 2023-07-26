<template>
    <div class="update-container">
        <div class="step-container">
            <n-steps vertical :current="stepData.step" :status="stepData.status">
                <n-step v-for="(item, index) in stepData.list" :key="index" :title="item.title" />
            </n-steps>
        </div>
        <div class="content-container" v-if="pageData">
            <img :src="pageData.logo" class="logo" alt="">
            <div class="title">{{ pageData.title }} {{ pageData.version_name }}</div>
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
                        step: 'download',
                        title: '下载安装包',
                    },
                    {
                        step: 'unzip',
                        title: '解压安装包',
                    },
                    {
                        step: 'updateData',
                        title: '数据安装',
                    },
                    {
                        step: 'reload',
                        title: '重启服务',
                    },
                    {
                        step: 'ping',
                        title: '等待重启',
                    },
                    {
                        step: 'success',
                        title: '安装完成',
                    },
                ],
            },
            pageData: null
        }
    },
    created() {
        this.detail()
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
            _this.$http.usePost(`admin/Plugin/install?step=${step}`, queryParams).then((res) => {
                if (res?.code === 200) {
                    if (res.data.next === 'success') {
                        const stepIndex = _this.stepData.list.findIndex(item => item.step === res.data.next) + 1;
                        _this.stepData.step = stepIndex
                        setTimeout(() => {
                            _this.$emit("update:closeWin");
                        }, 2000);
                        _this.stepData.stepText = res.msg;
                        _this.$useNotification?.success({
                            title: res?.msg ?? "操作成功",
                            duration: 1500,
                        });
                    } else if (res.data.next) {
                        _this.exceStep(res.data.next);
                    }
                } else {
                    _this.$useNotification?.error({
                        title: res?.msg ?? "操作成功",
                        duration: 1500,
                        onAfterLeave: () => {
                            _this.$emit("update:closeWin");
                        }
                    });
                }
            }).catch((err) => {
                console.error('error', err)
                let step = '';
                if (err?.response?.status === 502) {
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
                    setTimeout(() => {
                        _this.$emit("update:closeWin");
                    }, 1500);
                }
            })
        },
        detail() {
            const _this = this;
            const queryParams = {
                ...this.ajaxParams,
            };
            _this.$http
                .useGet("admin/Plugin/detail", queryParams)
                .then((res) => {
                    if (res.code === 200) {
                        _this.pageData = res?.data ?? {};
                        _this.exceStep('download')
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
                    } else {
                        _this.$emit("update:closeWin");
                    }
                });
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
        padding: 0 20px;
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
  