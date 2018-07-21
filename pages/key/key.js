// pages/welcome/welcome.js
function login_fun(){
  wx.login({
    success: function (res) {
      // console.log(res.code)
      if (res.code) {
        //发起网络请求
        wx.request({
          url: 'https://m.ctrltab.xyz/bid_info/onlogin',
          data: {
            code: res.code,
            face: wx.getStorageSync('userpicture'),
            u_place: this.data. key1,
            u_ind_type: this.data.key2,
            timelong: this.data.key3
          },
          header: {
            "content-type": "application/x-www-form-urlencoded" // 默认值
          },
          method: "POST",
          success: function (res) {
            console.log(res.data)
            wx.setStorageSync('id', res.data.id);
          },
          fail: function () {
            console.log("发送失败");
          }
        })
      } else {
        console.log('登录失败！' + res.errMsg)
      }
    }
  });
}
Page({

  /**
   * 页面的初始数据
   */
  data: {
    key1:"",
    key2:"",
    key3:""
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
  
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
  
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {
  
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {
  
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
  
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
  
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
  
  },
  key1: function (e) {
    this.setData({
      key1: e.detail.value
    })
  },
  key2: function (e) {
    this.setData({
      key2: e.detail.value
    })
  },
  key3: function (e) {
    this.setData({
      key3: e.detail.value
    })
  },
  finish:function(){
    login_fun();
    wx.redirectTo({
      url: '../index/index',
    })
  },
  skip: function () {
    login_fun();
    wx.redirectTo({
      url: '../index/index',
    })
  }
})