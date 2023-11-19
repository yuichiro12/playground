# %%
import matplotlib.animation as animation
import matplotlib.pyplot as plt
import numpy as np
from IPython.display import HTML

plt.rcParams["font.family"] = "Hiragino sans"
# linspace(min, max, N) で範囲 min から max を N 分割します
x1 = np.linspace(0, 3.5, 100)
# f'(x) = (x-3)(x-2/3)(x-2) となる
def zero(x): return x * 0
def f(x): return 1/4.0*x**4 - 17/9.0*x**3 + 28/6.0*x**2 - 4*x + 1
def fprime(x): return (x-3)*(x-2/3.0)*(x-2)
def tangent(x, p): return fprime(p)*(x-p) + f(p)
def arg_zero_tangent(p): return -f(p)/fprime(p) + p


y1 = f(x1)

plt.figure(figsize=(6, 4), dpi=100)
plt.plot(x1, y1, label='目的関数')
plt.plot(3, f(3), marker='.', mfc='black',
         mec='black', label='局所最適解', linewidth=0)
plt.plot(2/3.0, f(2/3.0), marker='.', mfc='red',
         mec='red', label='大域最適解', linewidth=0)
plt.legend(loc='upper right', fontsize=10, labelspacing=0.7, handlelength=2)
plt.show()

# %%
fig = plt.figure(figsize=(6, 4), dpi=100)
ax = fig.add_subplot()
plt.xlim(0.0, 2)
plt.ylim(0.0, 1.55)


def draw_f(): ax.plot(x1, y1)
def draw_p(t): ax.plot(t, f(t), c="black")
def draw_tangent(t): ax.plot(x1, tangent(x1, t), c="red")
def draw_arg_zero_p(t): ax.plot(t, 0, c="black")


t = 1.5
for i in range(4):
    draw_f()
    draw_p(t)
    draw_tangent(t)
    t = arg_zero_tangent(t)
    draw_arg_zero_p(t)

plt.show()


# %%

# Figureを追加
fig = plt.figure(figsize=(15, 15), dpi=130)
# FigureにAxesを追加
ax = fig.add_subplot(111, projection='3d')
ax.computed_zorder = False
ax.set_zlim(-0.5, 2.5)
# 分割数
n = 300
# 格子点の作成
_x = np.linspace(-10, 10, n)
_y = np.linspace(-10, 10, n)
x, y = np.meshgrid(_x, _y)
# 計算式


def g(x: float, y: float):
    return (np.sin(x)/x * np.sin(y)/y + 1)**(1.5*x)/np.pi


# 曲面を描画
ax.set_xlabel('x')
ax.set_ylabel('y')
ax.set_zlabel('f(x, y)')

ax.plot_surface(x, y, g(x, y), cmap="plasma", zorder=0)
v = ((-4, -2), (-4.3, -1.7), (-4.5, -1.3), (-4.6, -1.0),
     (-4.67, -0.7), (-4.7, -0.3), (-4.72, -0.1), (-4.71, -0.01))


def draw(i):
    for j in range(i+1):
        ax.plot(v[j][0], v[j][1], g(v[j][0], v[j][1]),
                marker=".", color='black', zorder=2)
        if j != 0 and j < len(v):
            ax.plot([v[j-1][0], v[j][0]], [v[j-1][1], v[j][1]],
                    [g(v[j-1][0], v[j-1][1]), g(v[j][0], v[j][1])], c="blue", zorder=1)


ani = animation.FuncAnimation(fig, draw, interval=500, frames=len(v))
ani.save("gradient_method.gif", writer='imagemagick')

# %%
# Figureを追加
fig = plt.figure(figsize=(15, 15), dpi=130)
# FigureにAxesを追加
ax = fig.add_subplot(111, projection='3d')
ax.computed_zorder = False
ax.set_zlim(-0.5, 2.5)
# 分割数
n = 300
# 格子点の作成
_x = np.linspace(-10, 10, n)
_y = np.linspace(-10, 10, n)
x, y = np.meshgrid(_x, _y)
# 計算式


def g(x: float, y: float):
    return (np.sin(x)/x * np.sin(y)/y + 1)**(1.5*x)/np.pi


# 曲面を描画
ax.set_xlabel('x')
ax.set_ylabel('y')
ax.set_zlabel('f(x, y)')

ax.plot_surface(x, y, g(x, y), cmap="plasma", zorder=0)
u = (-4.7, -0.01)
ax.plot(u[0], u[1], g(u[0], u[1]), marker=".", color='black', zorder=2)
