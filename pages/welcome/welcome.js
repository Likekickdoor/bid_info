// pages/welcome/welcome.js
Page({
  data: {
    canIUse: wx.canIUse('button.open-type.getUserInfo'),
  },
  onLoad: function () {
    // wx.login({
    //   success: function (res) {
    //     // console.log(res.code)
    //     if (res.code) {
    //       //发起网络请求
    //       wx.request({
    //         url: 'https://settime.kermi.xyz/frame/web/?r=user/login',
    //         data: {
    //           code: res.code
    //         },
    //         header: {
    //           "content-type": "application/x-www-form-urlencoded" // 默认值
    //         },
    //         method: "POST",
    //         success: function (res) {
    //           console.log(res.data)
    //           wx.setStorageSync('id', res.data.id);
    //         },
    //         fail: function () {
    //           console.log("发送失败");
    //         }
    //       })
    //     } else {
    //       console.log('登录失败！' + res.errMsg)
    //     }
    //   }
    // });
    // // 查看是否授权
    // wx.getSetting({
    //   success: function (res) {
    //     console.log(res.authSetting['scope.userInfo'])
    //     if (res.authSetting['scope.userInfo']) {
    //       // 已经授权，可以直接调用 getUserInfo 获取头像昵称
    //       wx.getUserInfo({
    //         success: function (res) {
    //           wx.switchTab({//关闭当前页，跳到不相干的页面，没有返回
    //             url: '../index/index'
    //           })
    //           console.log(res.userInfo);
    //           wx.setStorageSync('userName', res.userInfo.nickName);
    //           wx.setStorageSync('userpicture', res.userInfo.avatarUrl);
    //         }
    //       })
    //     }
    //   }
    // })
  },
  bindGetUserInfo: function (e) {
    console.log(e.detail.userInfo)
    wx.setStorageSync('userName', e.detail.userInfo.nickName);
    wx.setStorageSync('userpicture', e.detail.userInfo.avatarUrl);
    wx.switchTab({//关闭当前页，跳到不相干的页面，没有返回
      url: '../index/index'
    })
  },
  into_home: function () {
    wx.switchTab({//关闭当前页，跳到不相干的页面，没有返回
      url: '../index/index'
    })
  }
})