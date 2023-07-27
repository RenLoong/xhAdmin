<template>
    <div class="app-container">
        <!-- 平台应用 -->
        <div class="platform-count">
            <div class="platform-title">
                <div class="title-box">
                    <AppIcons icon="FundOutlined" :size="18" />
                    <span class="title">项目数据统计</span>
                </div>
                <div class="tools-box">
                    <AppIcons icon="QuestionCircleOutlined" :size="16" />
                    <span class="tips">点击一下看板创建对应的项目</span>
                </div>
            </div>
            <n-grid :cols="6" :x-gap="24" :y-gap="12" class="num-container">
                <n-grid-item class="item" v-for="(item, index) in platformApp" :key="index" @click="hanldCreated(item.key)">
                    <div class="logo-container">
                        <img :src="item.logo" class="logo" alt="">
                    </div>
                    <div class="content">
                        <n-statistic tabular-nums>
                            <template #label>
                                <div class="count-label">
                                    {{ item.label }}
                                </div>
                            </template>
                            <div class="data-number">
                                <n-number-animation show-separator :from="0" :to="item.created" />
                                <div class="separator">/</div>
                                <n-number-animation show-separator :from="0" :to="item.num" />
                            </div>
                        </n-statistic>
                    </div>
                </n-grid-item>
            </n-grid>
        </div>
        <!-- 项目管理 -->
        <div class="projects-list">
            <div class="project-tools-box">
                <div class="project-title">
                    <AppIcons icon="CodeSandboxOutlined" :size="16" />
                    <span class="title">项目看板</span>
                </div>
                <div class="project-tools">
                    <!-- <button class="action-btn create-project">
                        <AppIcons icon="PlusOutlined" :size="16" />
                        <span class="title">创建项目</span>
                    </button> -->
                    <n-dropdown trigger="hover" :options="platforms" @select="handleSelectPlatform">
                        <button class="action-btn all-project">
                            <AppIcons icon="CaretDownOutlined" :size="16" />
                            <span class="title">{{ selectPlatform }}</span>
                        </button>
                    </n-dropdown>
                </div>
            </div>
            <div class="project-content" v-if="projects.list.length">
                <div class="item" v-for="(item, index) in projects.list" :key="index">
                    <div class="project-info">
                        <n-image :src="item.logo" width="150" height="150" class="logo" />
                        <img :src="item.platformLogo" class="platform-type" alt="" />
                        <div class="title">{{ item.title }}</div>
                        <!-- async_data为真则需要同步旧版本数据 -->
                        <div class="async-data" @click="hanldAsyncData(item)" v-if="item.async_data">
                            点击同步配置数据
                        </div>
                        <div class="help-tools" v-if="item.isSetting || appletType.includes(item.platform)">
                            <div class="help-box">
                                <n-tooltip trigger="hover" placement="top" v-if="item.isSetting">
                                    <template #trigger>
                                        <div class="item" @click="hanldOepn('/StoreApp/config', item)">
                                            <AppIcons icon="SettingOutlined" :size="19" />
                                        </div>
                                    </template>
                                    项目设置
                                </n-tooltip>
                                <n-tooltip trigger="hover" placement="top" v-if="appletType.includes(item.platform)">
                                    <template #trigger>
                                        <div class="item" @click="hanldOepn('/StoreApp/applet', item)">
                                            <AppIcons icon="CloudUploadOutlined" :size="19" />
                                        </div>
                                    </template>
                                    小程序配置
                                </n-tooltip>
                            </div>
                        </div>
                        <div class="admin-tools" v-if="!item.async_data">
                            <n-tooltip trigger="hover" placement="right">
                                <template #trigger>
                                    <div class="action-item" @click="hanldAdmin(item)">
                                        <AppIcons icon="PartitionOutlined" :size="20" color="#666" />
                                    </div>
                                </template>
                                登录管理
                            </n-tooltip>
                            <n-tooltip trigger="hover" placement="right">
                                <template #trigger>
                                    <div class="action-item" @click="copyAppsUrl(item)">
                                        <AppIcons icon="LinkOutlined" :size="20" color="#666" />
                                    </div>
                                </template>
                                复制项目连接
                            </n-tooltip>
                            <n-tooltip trigger="hover" placement="right">
                                <template #trigger>
                                    <div class="action-item" @click="hanldOepn('/StoreApp/edit', item)">
                                        <AppIcons icon="EditOutlined" :size="20" color="#666" />
                                    </div>
                                </template>
                                修改项目
                            </n-tooltip>
                            <n-tooltip trigger="hover" placement="right">
                                <template #trigger>
                                    <div class="action-item" @click="hanldDel(item)">
                                        <AppIcons icon="DeleteOutlined" :size="20" color="#666" />
                                    </div>
                                </template>
                                删除项目
                            </n-tooltip>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 数据为空 -->
            <div class="result-empty" v-else>
                <n-result status="404" description="当前没有更多的项目">
                </n-result>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            platformApp: [],
            platforms: [
                {
                    label: '全部项目',
                    key: ''
                },
            ],
            // 支持小程序工具栏显示
            appletType:[
                'mini_wechat',
                'douyin'
            ],
            projects: {
                platformActive: '',
                list: [],
            },
        };
    },
    async created() {
        const _this = this;
        await _this.getCount();
        await _this.getList();
    },
    computed: {
        selectPlatform() {
            const { platformActive } = this.projects;
            const platform = this.platforms[platformActive];
            return platform ? platform.label : '全部项目';
        },
    },
    methods: {
        // 旧版本项目数据同步
        hanldAsyncData(e) {
            const _this = this;
            _this.$useDialog.create({
                type: "warning",
                title: "温馨提示",
                content: "是否确认同步旧版本项目配置数据？",
                positiveText: "确定",
                negativeText: "取消",
                maskClosable: false,
                onPositiveClick() {
                    _this.$http.usePost('store/StoreApp/asyncData', { id: e.id }).then((res) => {
                        _this.$useNotification?.success({
                            title: res?.msg ?? "操作成功",
                            duration: 1500,
                        });
                        _this.getList();
                    })
                },
            });
        },
        // 执行删除项目
        actionDelProject(e) {
            const _this = this;
            const { id } = e;
            _this.$http.useDelete('store/StoreApp/del', { id }).then((res) => {
                _this.platformActive = ''
                _this.getCount();
                _this.getList();
                _this.$useNotification?.success({
                    title: res?.msg ?? "操作成功",
                    duration: 1500,
                });
            })
        },
        // 删除项目
        hanldDel(e) {
            const _this = this;
            _this.$useDialog.create({
                type: "warning",
                title: "温馨提示",
                content: "是否确定删除该项目？",
                positiveText: "确定",
                negativeText: "取消",
                maskClosable: false,
                onPositiveClick() {
                    _this.actionDelProject(e);
                },
            });
        },
        // 跳转项目后台
        hanldAdmin(e) {
            const _this = this;
            const params = {
                appid_id: e.id,
            }
            _this.$http.usePost('store/StoreApp/login', params).then((res) => {
                if (res.code === 200) {
                    const { data } = res;
                    if (data?.url) {
                        window.open(data?.url)
                    } else {
                        _this.$useNotification?.error({
                            title: '登录项目管理失败',
                            duration: 1500,
                        });
                    }
                } else {
                    _this.$useNotification?.error({
                        title: res?.msg ?? '获取数据失败',
                        duration: 1500,
                    });
                }
            })
        },
        // 复制项目连接
        copyAppsUrl(e) {
            const copyText = `http://${window.location.host}/app/${e.name}/#/?appid=${e.id}`;
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(copyText).then(_ => {
                    this.$useNotification?.success({
                        title: '已复制',
                        duration: 1500,
                    });
                })
            } else {
                let input = document.createElement('input');
                input.value = copyText;
                document.body.appendChild(input);
                input.select();
                document.execCommand('Copy');
                input.remove();
                this.$useNotification?.success({
                    title: '已复制',
                    duration: 1500,
                });
            }
        },
        // 跳转页面
        hanldOepn(path, item) {
            this.$routerApp.push({
                path: path,
                query: {
                    id: item?.id,
                    isBack: 1
                }
            });
        },
        // 跳转创建平台
        hanldCreated(platform) {
            this.$routerApp.push({
                path: '/StoreApp/create',
                query: {
                    platform: platform,
                    isBack: 1
                }
            });
        },
        // 选择平台
        handleSelectPlatform(e) {
            const platformIndex = this.platforms.findIndex((item) => item.key === e);
            this.projects.platformActive = platformIndex;
            this.getList();
        },
        // 获取项目列表
        getList() {
            const _this = this;
            const platform = _this.platforms[_this.projects.platformActive];
            const params = {
                platform: platform?.key,
            }
            _this.$http.useGet('store/StoreApp/index', params).then((res) => {
                const { data } = res;
                _this.projects.list = data;
            })
        },
        // 获取数据统计
        getCount() {
            const _this = this;
            _this.$http.useGet('store/Index/countData').then((res) => {
                const { data } = res;
                const { platformApp } = data;
                _this.platformApp = platformApp
                _this.platforms.push(...platformApp);
            })
        },
    },
};
</script>

<style lang="scss" scoped>
.app-container {
    height: 100%;
    display: flex;
    flex-direction: column;

    .platform-count {
        height: 22%;

        .platform-title {
            background: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            user-select: none;

            .title-box {
                font-weight: 700;
                font-size: 16px;
                display: flex;
                align-items: center;

                .title {
                    padding-left: 5px;
                }
            }

            .tools-box {
                font-size: 12px;
                color: #FF7D00;
                display: flex;
                align-items: center;

                .tips {
                    padding-left: 5px;
                }
            }
        }

        .num-container {
            .item {
                margin-top: 10px;
                display: flex;
                justify-content: center;
                align-items: center;
                border-radius: 5px;
                background: #fff;
                cursor: pointer;

                &:hover {
                    background: #ffffff91;
                }

                .logo-container {
                    width: 120px;
                    height: 60px;
                    display: flex;
                    justify-content: center;
                    align-items: center;

                    .logo {
                        width: 60px;
                        height: 60px;
                        user-select: none;
                        pointer-events: none;
                    }
                }

                .content {
                    flex: 1;
                    padding: 25px 0;

                    .count-label {
                        width: 150px;
                        text-align: center;
                        font-size: 1rem;
                        user-select: none;
                    }

                    .data-number {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        user-select: none;

                        .separator {
                            padding: 0 5px;
                        }
                    }

                    .n-statistic-value {
                        width: 150px;
                        text-align: center;

                        .n-statistic-value__content {
                            user-select: none;
                        }
                    }
                }
            }
        }
    }

    .projects-list {
        height: 78%;
        background: #fff;
        display: flex;
        flex-direction: column;

        .project-tools-box {
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #e5e5e5;

            .project-title {
                display: flex;
                align-items: center;

                .title {
                    font-size: 16px;
                    font-weight: 700;
                    padding-left: 2px;
                }
            }

            .project-tools {
                display: flex;
                align-items: center;
                justify-content: flex-end;
                gap: 20px;

                .action-btn {
                    padding: 4px 10px;
                    border-radius: 5px;
                    background: #fff;
                    cursor: pointer;
                    transition: all .3s;
                    display: flex;
                    align-items: center;
                    justify-content: center;

                    .title {
                        padding-left: 2px;
                    }

                    &:hover {
                        color: #fff;
                    }
                }

                .all-project {
                    border: 1px solid #F53F3F;
                    color: #F53F3F;

                    &:hover {
                        background: #F53F3F;
                    }
                }

                .create-project {
                    border: 1px solid #722ED1;
                    color: #722ED1;

                    &:hover {
                        background: #722ED1;
                    }
                }
            }
        }

        .result-empty {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1;
        }

        .project-content {
            height: 100%;
            display: flex;
            flex-wrap: wrap;
            gap: 0 45px;
            padding: 30px 10px;
            overflow-y: auto;
            overflow-x: hidden;

            .item {
                flex-basis: 12%;
                display: flex;
                justify-content: center;

                .project-info {
                    position: relative;
                    border: 1px solid #e8e8e8;
                    border-radius: 5px;
                    width: 150px;
                    height: 150px;

                    .logo {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        border-radius: 5px;
                        width: 150px;
                        height: 150px;
                        background: #dadce0;
                    }

                    .platform-type {
                        width: 26px;
                        height: 26px;
                        position: absolute;
                        top: 5px;
                        left: 5px;
                    }

                    .title {
                        width: 100%;
                        padding: 3px 0;
                        font-size: 14px;
                        border-radius: 0 0 5px 5px;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                        color:#666;
                    }
                    .async-data{
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        position: absolute;
                        inset: 0;
                        background: rgba(#000000, .4);
                        color: #fff;
                        cursor: pointer;
                        user-select:none;
                        border-radius: 5px;
                        z-index: 9999;
                    }

                    .help-tools {
                        position: absolute;
                        left: 0;
                        bottom: 0;
                        right: 0;
                        user-select: none;
                        cursor: pointer;
                        background: rgba(#000000, .4);
                        border-bottom-left-radius: 5px;
                        border-bottom-right-radius: 5px;
                        // display: none;

                        .help-box {
                            display: flex;
                            justify-content: space-between;
                            padding: 4px 5px;

                            .item {
                                .n-icon {
                                    color: #fff;
                                }
                            }
                        }
                    }

                    .admin-tools {
                        position: absolute;
                        top: 0;
                        right: -35px;
                        bottom: 0;
                        display: flex;
                        flex-direction: column;
                        justify-content: space-between;
                        padding: 0 8px;
                        user-select: none;
                        cursor: pointer;

                        .action-item {
                            display: flex;
                            justify-content: center;
                            align-items: center;
                        }
                    }

                    &:hover {
                        .help-tools {
                            display: block;
                        }
                    }

                }
            }
        }
    }
}

.a-link {
    color: #555;
}

.a-link:hover {
    color: #0eca62;
}
</style>
