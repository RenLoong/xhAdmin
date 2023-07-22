<template>
    <div class="app-container">
        <!-- 平台应用 -->
        <div class="platform-count">
            <n-grid :cols="5" :x-gap="24" :y-gap="12" class="num-container">
                <n-grid-item class="item wechat">
                    <div class="logo-container">
                        <img src="/image/new_wechat.png" class="logo" alt="">
                    </div>
                    <div class="content">
                        <n-statistic tabular-nums>
                            <template #label>
                                <div class="count-label">
                                    可创建微信公众号
                                </div>
                            </template>
                            <n-number-animation show-separator :from="0" :to="platformApp.wechat" />
                        </n-statistic>
                    </div>
                </n-grid-item>
                <n-grid-item class="item mini-wechat">
                    <div class="logo-container">
                        <img src="/image/new_wx_mini.png" class="logo" alt="">
                    </div>
                    <div class="content">
                        <n-statistic tabular-nums>
                            <template #label>
                                <div class="count-label">
                                    可创建微信小程序
                                </div>
                            </template>
                            <n-number-animation show-separator :from="0" :to="platformApp.mini_wechat" />
                        </n-statistic>
                    </div>
                </n-grid-item>
                <n-grid-item class="item douyin">
                    <div class="logo-container">
                        <img src="/image/douyin.png" class="logo" alt="">
                    </div>
                    <div class="content">
                        <n-statistic tabular-nums>
                            <template #label>
                                <div class="count-label">
                                    可创建抖音小程序
                                </div>
                            </template>
                            <n-number-animation show-separator :from="0" :to="platformApp.douyin" />
                        </n-statistic>
                    </div>
                </n-grid-item>
                <n-grid-item class="item h5">
                    <div class="logo-container">
                        <img src="/image/h5.png" class="logo" alt="">
                    </div>
                    <div class="content">
                        <n-statistic tabular-nums>
                            <template #label>
                                <div class="count-label">
                                    可创建网页应用
                                </div>
                            </template>
                            <n-number-animation show-separator :from="0" :to="platformApp.h5" />
                        </n-statistic>
                    </div>
                </n-grid-item>
                <n-grid-item class="item other">
                    <div class="logo-container">
                        <img src="/image/other.png" class="logo" alt="">
                    </div>
                    <div class="content">
                        <n-statistic tabular-nums>
                            <template #label>
                                <div class="count-label">
                                    可创建其他应用
                                </div>
                            </template>
                            <n-number-animation show-separator :from="0" :to="platformApp.other" />
                        </n-statistic>
                    </div>
                </n-grid-item>
            </n-grid>
        </div>
        <!-- 项目管理 -->
        <div class="projects-list">
            <div class="project-tools-box">
                <div class="project-title">
                    <div class="item buttons">
                        <AppIcons icon="CodeSandboxOutlined" :size="16" />
                        <span class="title">项目管理</span>
                    </div>
                    <div class="item">
                        <n-select :options="platforms" />
                    </div>
                </div>
                <div class="project-tools">
                    <button class="action-btn">
                        <AppIcons icon="PlusOutlined" :size="16" />
                        <span class="title">创建项目</span>
                    </button>
                </div>
            </div>
            <div class="project-content">
                <div class="item" v-for="(item, index) in 20" :key="index">
                    <div class="project-info">
                        <n-image src="http://demo.kfadmin.net/image/logo.png" width="150" height="150" class="logo" />
                        <img src="/image/other.png" class="platform-type" alt="" />
                        <div class="title">标题标题标题标题标题标题标题</div>
                        <div class="tools">
                            <n-tooltip trigger="hover" placement="right">
                                <template #trigger>
                                    <div @click="hanldAdmin(item)">
                                        <AppIcons icon="PartitionOutlined" :size="20" color="#888" />
                                    </div>
                                </template>
                                登录管理
                            </n-tooltip>
                            <n-tooltip trigger="hover" placement="right">
                                <template #trigger>
                                    <div @click="copyAppsUrl(item)">
                                        <AppIcons icon="LinkOutlined" :size="20" color="#888" />
                                    </div>
                                </template>
                                复制应用连接
                            </n-tooltip>
                            <n-tooltip trigger="hover" placement="right">
                                <template #trigger>
                                    <div @click="hanldEdit(item)">
                                        <AppIcons icon="EditOutlined" :size="20" color="#888" />
                                    </div>
                                </template>
                                修改应用
                            </n-tooltip>
                            <n-tooltip trigger="hover" placement="right">
                                <template #trigger>
                                    <div @click="hanldStop(item)">
                                        <AppIcons icon="SettingOutlined" :size="20" color="#888" />
                                    </div>
                                </template>
                                停止或启用
                            </n-tooltip>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            platformApp: {
                wechat: 0,
                mini_wechat: 0,
                douyin: 0,
                h5: 0,
                other: 0,
            },
            platforms: [
                {
                    label: '全部项目',
                    value: ''
                },
                {
                    label: '微信公众号',
                    value: 'wechat'
                },
                {
                    label: '微信小程序',
                    value: 'wechat_mini'
                },
                {
                    label: '抖音小程序',
                    value: 'douyin'
                },
                {
                    label: '网页应用',
                    value: 'h5'
                },
                {
                    label: '其他应用',
                    value: 'other'
                },
            ],
        };
    },
    created() {
        this.initify();
    },
    methods: {
        initify() {
            const _this = this;
            _this.$http.useGet('store/Index/consoleCount').then((res) => {
                const { data } = res;
                _this.platformApp = data.platformApp
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
        height: 18%;

        .num-container {
            .item {
                margin-top: 20px;
                display: flex;
                justify-content: center;
                align-items: center;
                border-radius: 5px;
                background: #fff;

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
        height: 82%;
        background: #fff;
        display: flex;
        flex-direction: column;

        .project-tools-box {
            padding: 10px 20px;
            display: flex;
            border-bottom: 1px solid #e5e5e5;

            .project-title {
                width: 50%;
                .buttons {
                    display: flex;
                    align-items: center;

                    .title {
                        font-size: 16px;
                        font-weight: 700;
                        padding-left: 2px;
                    }
                }
            }

            .project-tools {
                width: 50%;
                display: flex;
                align-items: center;
                justify-content: flex-end;
                gap: 10px;

                .action-btn {
                    padding: 4px 10px;
                    border-radius: 5px;
                    border: 1px solid #722ED1;
                    color: #722ED1;
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
                        background: #722ED1;
                    }
                }
            }
        }

        .project-content {
            height: 100%;
            display: flex;
            flex-wrap: wrap;
            gap: 30px 10px;
            padding: 10px;
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
                        width: 20px;
                        height: 20px;
                        position: absolute;
                        top: 3px;
                        left: 3px;
                    }

                    .title {
                        position: absolute;
                        left: 0;
                        bottom: 0;
                        width: 100%;
                        padding: 3px 5px;
                        background: rgba(0, 0, 0, .5);
                        color: #fff;
                        font-size: 14px;
                        font-weight: 700;
                        text-align: center;
                        border-radius: 0 0 5px 5px;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                    }

                    .tools {
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
