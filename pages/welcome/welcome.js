// pages/welcome/welcome.js
Page({
  data: {
    canIUse: wx.canIUse('button.open-type.getUserInfo'),
  },
  onLoad: function () {

    // 查看是否授权
    wx.getSetting({
      success: function (res) {
        console.log(res.authSetting['scope.userInfo'])
        if (res.authSetting['scope.userInfo']) {
          // 已经授权，可以直接调用 getUserInfo 获取头像昵称
          wx.getUserInfo({
            success: function (res) {
              wx.switchTab({//关闭当前页，跳到不相干的页面，没有返回
                url: '../index/index'
              })
              console.log(res.userInfo);
              wx.setStorageSync('userName', res.userInfo.nickName);
              wx.setStorageSync('userpicture', res.userInfo.avatarUrl);
            }
          })
        }
      }
    })
  },
  bindGetUserInfo: function (e) {
    console.log(e.detail.userInfo)
    if (e.detail.userInfo){
      wx.setStorageSync('userName', e.detail.userInfo.nickName);
      wx.setStorageSync('userpicture', e.detail.userInfo.avatarUrl);
      wx.redirectTo({//关闭当前页，跳到不相干的页面，没有返回
        url: '../key/key'
      })
    }else{
      wx.switchTab({//关闭当前页，跳到不相干的页面，没有返回
        url: '../index/index'
      })
    }

  },
  into_home: function () {
    wx.switchTab({//关闭当前页，跳到不相干的页面，没有返回
      url: '../index/index'
    })
  }
})