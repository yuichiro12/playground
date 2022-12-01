from math import log, sqrt
import matplotlib.pyplot as plt
import numpy as np
import scipy.stats as stats
import pandas as pd
from pandas import DataFrame, Series, date_range

#%%
## 計算
a = 2
b = 2
x = np.linspace(0, 1, 100) #x軸
y = stats.beta.pdf(x, a, b)      #y軸

plt.plot(x, y)

#%%

## 計算
x = np.linspace(-4, 4, 100) #x軸
y = stats.norm.pdf(x)

plt.plot(x, y)

# %%

class Slot:
	exp = 0

	def __init__(self, exp):
		self.exp = exp

	def run(self) -> float:
		return stats.norm.rvs(self.exp)


slots = Slot(0), Slot(0.5), Slot(1), Slot(1.5), Slot(2)
T = 1000

def epsilon_greedy() -> None:
	EPSILON = 0.2
	scores = [0 for _ in slots]
	score = 0

	for t in range(int(EPSILON*T/len(slots))):
		for j, slot in enumerate(slots):
			scores[j] += slot.run()
	
	print(scores)
	print(np.argmax(scores))
	score += sum(scores)
	maxslot = slots[np.argmax(scores)]

	for t in range(int((1-EPSILON)*T)):
		score += maxslot.run()

	print(score)

epsilon_greedy()

def ucb() -> None:
	scores = [{"score": 0, "num": 0} for _ in slots]
	ucb_scores = [0 for _ in slots]
	for i, slot in enumerate(slots):
		scores[i]["score"] += slot.run()
		scores[i]["num"] += 1
	
	for t in range(len(slots)+1, T+1):
		for i, slot in enumerate(slots):
			mu_t = scores[i]["score"] / scores[i]["num"]
			ucb_scores[i] = mu_t + np.sqrt(np.log(t) / (2*scores[i]["num"]))

		max_i = np.argmax(ucb_scores)
		scores[max_i]["score"] += slots[max_i].run()
		scores[max_i]["num"] += 1

		if t % 100 == 0:
			print(t, ucb_scores, max_i)

	score = 0
	for s in scores:
		score += s["score"]
	print(score)

ucb()

def gaussian_thompson_sampling() -> None:
	scores = [{"score": 0, "num": 0} for _ in slots]
	score = 0
	a = 50000
	b = 50000
	n, m = 0, 0
	for i in range(T):
		rands = []
		for slot in slots:
			rands.append(stats.invgamma(a=a+m, b=b-m))
		
		slots[np.argmax(rands)].run()

		n += 1
		m += 2

			
# %%

