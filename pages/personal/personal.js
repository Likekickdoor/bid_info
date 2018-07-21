<<<<<<< HEAD

Page({
  navigateSkip: function () {
    wx.navigateTo({
      title: "goback",
      url: '../collection/collection'
    })
  },
  navigateBack: function () {
    wx.navigateTo({
      title: "goback",
      url: '../collection/collection'
    })
  },
  Back: function () {
    wx.navigateTo({
      title: "goback",
      url: '../history/history'
    })
  },
  
})
=======
// pages/index/index.js
Page({

      navigateBack: function() {
        wx.navigateTo({
          title: "goback",
          url: '../collection/collection'
        })
      },
      Back: function() {
        wx.navigateTo({
          title: "goback",
          url: '../history/history'
        })
      }})
>>>>>>> 413712723a4797bcd10345ad0e80d5e4e1efd294
