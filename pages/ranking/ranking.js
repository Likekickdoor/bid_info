Page({ 
  /**
   * 页面的初始数据
   */
  data: {
    showView: true,
    showView1: false,
    bg: true,
    sview: false,
    view: true,
  },
  // 点击按钮切换榜单
  showButton: function () {
    var that = this;
    that.setData({
      showView: true,
      showView1: false,
      bg: true,
    })
  },
  showButton1: function () {
    var that = this;
    that.setData({
      showView: false,
      showView1: true,
      bg: false,
    })
  },
})