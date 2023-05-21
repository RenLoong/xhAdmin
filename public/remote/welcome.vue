<template>
  <div class="app-container">
    <!-- 平台应用 -->
    <div class="platform-count">
      <n-grid :cols="5" :x-gap="24" :y-gap="12" class="num-container">
        <n-grid-item class="item">
          <div class="logo-container">
            <img src="/image/new_wechat.png" class="logo" alt="">
          </div>
          <div class="content">
            <n-statistic tabular-nums>
              <template #label>
                <div class="count-label">
                  微信公众号
                </div>
              </template>
              <n-number-animation show-separator :from="0" :to="platformApp.wechat" />
            </n-statistic>
          </div>
        </n-grid-item>
        <n-grid-item class="item">
          <div class="logo-container">
            <img src="/image/new_wx_mini.png" class="logo" alt="">
          </div>
          <div class="content">
            <n-statistic tabular-nums>
              <template #label>
                <div class="count-label">
                  微信小程序
                </div>
              </template>
              <n-number-animation show-separator :from="0" :to="platformApp.mini_wechat" />
            </n-statistic>
          </div>
        </n-grid-item>
        <n-grid-item class="item">
          <div class="logo-container">
            <img src="/image/new_douyin.png" class="logo" alt="">
          </div>
          <div class="content">
            <n-statistic tabular-nums>
              <template #label>
                <div class="count-label">
                  抖音小程序
                </div>
              </template>
              <n-number-animation show-separator :from="0" :to="platformApp.douyin" />
            </n-statistic>
          </div>
        </n-grid-item>
        <n-grid-item class="item">
          <div class="logo-container">
            <img src="/image/new_h5.png" class="logo" alt="">
          </div>
          <div class="content">
            <n-statistic tabular-nums>
              <template #label>
                <div class="count-label">
                  网页应用
                </div>
              </template>
              <n-number-animation show-separator :from="0" :to="platformApp.h5" />
            </n-statistic>
          </div>
        </n-grid-item>
        <n-grid-item class="item">
          <div class="logo-container">
            <img src="/image/new_other.png" class="logo" alt="">
          </div>
          <div class="content">
            <n-statistic tabular-nums>
              <template #label>
                <div class="count-label">
                  其他应用
                </div>
              </template>
              <n-number-animation show-separator :from="0" :to="platformApp.other" />
            </n-statistic>
          </div>
        </n-grid-item>
      </n-grid>
    </div>
    <!-- 请求数据 -->
    <div class="http-container">
      <n-grid :cols="16" :x-gap="12" :y-gap="12" item-responsive>
        <n-grid-item span="11" class="item">
          <div style="width:100%;height:500px;" ref="httpRef"></div>
        </n-grid-item>
        <n-grid-item span="5" class="item">
          <div class="data-item">
            <div class="title">开发团队</div>
            <n-table>
              <tbody>
                <tr v-for="(item, index) in teamTable" :key="index">
                  <td>{{ item.title }}</td>
                  <td v-html="item.values"></td>
                </tr>
              </tbody>
            </n-table>
          </div>
          <div class="data-item">
            <div class="title">产品动态</div>
            <n-data-table :columns="productTable.columns" :data="productTable.data" />
          </div>
        </n-grid-item>
      </n-grid>
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
      // 团队
      teamTable: [],
      // 产品动态
      productTable: {
        columns: [
          {
            title: '标题',
            key: 'title',
          },
          {
            title: '发布',
            width: 150,
            key: 'values',
          },
        ],
        data: [],
      },
      // 请求访问
      httpOptions: {
        title: {
          text: '应用增涨'
        },
        tooltip: {
          trigger: 'axis'
        },
        legend: {
          data: [
            '微信公众号',
            '微信小程序',
            '抖音小程序',
            '网页应用',
            'APP应用',
            '其他应用',
          ]
        },
        grid: {
          left: '3%',
          right: '4%',
          bottom: '3%',
          containLabel: true
        },
        xAxis: {
          type: 'category',
          boundaryGap: false,
          data: ['今日', '本周', '本月', '季度内', '半年内', '今年']
        },
        yAxis: {
          type: 'value'
        },
        series: []
      }
    };
  },
  created() {
    this.initify();
  },
  methods: {
    // 处理团队渲染规则
    checkTeamRule(data) {
      return data.map((item) => {
        if (item.values instanceof Array) {
          let html = [];
          for (let index = 0; index < item.values.length; index++) {
            const element = item.values[index];
            html.push(`<a href="${element.url}" class="a-link" target="_blank">${element.name}</a>`)
          }
          item.values = html.join('，');
        }
        return item
      })
    },
    initify() {
      const _this = this;
      _this.$http.useGet('admin/Index/consoleCount').then((res) => {
        const { data } = res;
        _this.platformApp = data.platformApp
        _this.teamTable = _this.checkTeamRule(data.team)
        _this.httpOptions.series = data.platform_echarts
        _this.echats = this.$echarts.init(this.$refs.httpRef);
        _this.echats.setOption(this.httpOptions);
      })
    },
  },
};
</script>

<style lang="scss" scoped>
.app-container {
  height: 100%;

  .platform-count {
    padding: 0 0 20px 0;

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
            width: 100px;
            text-align: center;
            font-size: 1rem;
            user-select: none;
          }

          .n-statistic-value {
            width: 100px;
            text-align: center;

            .n-statistic-value__content {
              user-select: none;
            }
          }
        }
      }
    }
  }

  .http-container {
    margin-top: 10px;

    .item {
      background: #fff;
      padding: 20px;
      display: flex;
      flex-direction: column;
      gap: 12px;

      .data-item {
        .title {
          font-size: 18px;
          font-weight: 700;
          padding-bottom: 10px;
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
