<template>
    <div class="update-container">
        <!-- 更新UI -->
        <div class="update-box">
            <img :src="pageData.logo" class="logo" alt="">
            <div class="title">小程序在线更新</div>
            <div class="body">
                <div class="item">
                    <div class="label">APPID</div>
                    <div class="value">
                        <n-input placeholder="请输入APPID" :clearable="true" v-model:value="formData.applet_appid"></n-input>
                    </div>
                </div>
                <div class="item">
                    <div class="label">Secret</div>
                    <div class="value">
                        <n-input placeholder="请输入Secret" :clearable="true" v-model:value="formData.applet_secret"></n-input>
                    </div>
                </div>
                <div class="item">
                    <div class="label">privatekey</div>
                    <div class="value">
                        <n-input type="textarea" placeholder="请输入privatekey" rows="8" :clearable="true"
                            v-model:value="formData.applet_privatekey" />
                    </div>
                </div>
                <div class="item">
                    <div class="label">发布预览</div>
                    <div class="value">
                        <n-switch v-model:value="formData.applet_state" unchecked-value="10" checked-value="20">
                            <template #checked>
                                二维码预览
                            </template>
                            <template #unchecked>
                                无需预览
                            </template>
                        </n-switch>
                    </div>
                </div>
                <div class="submit">
                    <n-button type="warning" @click="hanldBack">
                        返回项目
                    </n-button>
                    <n-button type="success" @click="hanldSave" :disabled="submitStatus">
                        {{ submitStatus ? '提交中...' : '保存设置' }}
                    </n-button>
                    <n-button type="info" @click="hanldSubmit"
                        :disabled="(!formData.applet_appid || !formData.applet_secret || !formData.applet_privatekey) || submitStatus">
                        {{ submitStatus ? '提交中...' : '立即发布' }}
                    </n-button>
                </div>
            </div>
        </div>
        <!-- 预览二维码弹窗 -->
        <div class="priview-modal" v-if="pageData.preview && pageData.qrcode">
            <div class="preview-qrcode-box">
                <div class="preview-text">扫二维码预览</div>
                <img :src="pageData.qrcode" class="preview-qrcode" alt="">
                <button class="preview-close" @click="pageData.preview = false">关闭</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            submitStatus: false,
            pageData: {
                logo: '',
                preview: false,
            },
            formData: {
                applet_appid: '',
                applet_secret: '',
                applet_privatekey: '',
                applet_state: '',
            }
        }
    },
    created() {
        this.initify();
    },
    methods: {
        // 保存配置
        hanldSave() {
            const _this = this;
            _this.submitStatus = true;
            const url = `store/StoreApp/applet?id=${this.$routeApp.query?.id ?? ''}`
            this.$http.usePut(url, this.formData).then((res) => {
                _this.$useNotification?.success({
                    title: res?.msg ?? "操作成功",
                    duration: 1500,
                });
            }).finally(() => {
                _this.submitStatus = false;
            })
        },
        // 发布更新
        hanldSubmit() {
            const _this = this;
            _this.submitStatus = true;
            const url = `store/StoreApp/applet?id=${this.$routeApp.query?.id ?? ''}`
            const params = {
                preview: this.formData.applet_state,
                applet_appid: this.formData.applet_appid
            };
            this.$http.usePost(url, params).then((res) => {
                if (res?.data?.preview) {
                    _this.pageData.preview = true;
                    _this.pageData.qrcode = res?.data?.qrcode ?? '';
                }
                // 预览二维码
                _this.$useNotification?.success({
                    title: res?.msg ?? "操作成功",
                    duration: 1500,
                });
            }).finally(() => {
                _this.submitStatus = false;
            })
        },
        hanldBack() {
            this.$routerApp.back();
        },
        initify() {
            const params = {
                id: this.$routeApp.query?.id ?? '',
            }
            this.$http.useGet('store/StoreApp/applet', params).then((res) => {
                const { data } = res;
                this.pageData.logo = data?.logo ?? '';
                this.formData = data?.config ?? {};
            })
        }
    },
}
</script>

<style lang="scss">
.update-container {
    width: 100%;
    height: 100%;
    background-color: #fff;

    .update-box {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 20px 0;

        .logo {
            width: 80px;
            height: 80px;
            border-radius: 5px;
        }

        .title {
            font-size: 22px;
            color: #333;
            margin-bottom: 20px;
        }

        .body {
            width: 40%;
            display: flex;
            flex-direction: column;
            gap: 20px 0;

            .item {
                display: flex;
                align-items: center;
                justify-content: center;

                .label {
                    width: 80px;
                    text-align: right;
                    margin-right: 10px;
                    color: #666;
                }

                .value {
                    flex: 1;
                    text-align: left;
                    color: #333;
                }
            }

            .submit {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 20px;
                margin-top: 20px;
            }
        }
    }

    .priview-modal {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .6);

        .preview-qrcode-box {
            width: 300px;
            height: 300px;
            background: #fff;
            border-radius: 5px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px 0;

            .preview-text {
                font-size: 15px;
                color: #444;
                font-weight: 700;
            }

            .preview-qrcode {
                width: 210px;
                height: 210px;
            }

            .preview-close {
                background: #8d4be9;
                color: #fff;
                border-radius: 3px;
                transition: all .3s;
                padding: 2px 25px;

                &:hover {
                    background: #722ED1;
                }
            }
        }
    }
}
</style>