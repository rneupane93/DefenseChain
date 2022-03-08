from sklearn.model_selection import train_test_split
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
from sklearn import svm
from sklearn.metrics import classification_report, accuracy_score
from mlxtend.plotting import plot_decision_regions
from sklearn.metrics import confusion_matrix,ConfusionMatrixDisplay
from sklearn.model_selection import cross_val_score, GridSearchCV

#read the file
df = pd.read_csv(<<file here>>)
print(df.head())
print("\n")


#remove unwanted data
df = df[pd.to_numeric(df[<<attribute>>], errors='coerce').notnull()]
df[<<attribute>>]=df[<<attribute>>].astype('int')


#create the independent and dependent attributes and divide dataset
#remove target and unnecessary columns
df2 = df[[<<attributes>>]]
X = np.asarray(df2)
y = np.asarray(df['Class'])
X_train, X_test, y_train, y_test = train_test_split(X,np.ravel(y),test_size=0.3, random_state=101)

param_grid = {'C': [0.1,1,10,100,1000],
	'gamma': [1,0.1,0.01,0.001,0.0001],
	'kernel': ['rbf','linear']}

grid = GridSearchCV(svm.SVC(), param_grid, refit = True, verbose =3)

grid.fit (X_train, y_train)
print(grid.best_params_)

print(grid.best_estimator_)
grid_predictions = grid.predict(X_test)


# Generate confusion matrix
matrix = confusion_matrix(y_test, grid_predictions, labels=grid.classes_)
disp = ConfusionMatrixDisplay (confusion_matrix=matrix, display_labels=grid.classes_)
disp.plot()
plt.show()

print(classification_report(y_test, grid_predictions))
print(accuracy_score(y_test, grid_predictions))

