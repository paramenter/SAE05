import pytest
from main import my_function  # Remplace par la fonction réelle à tester

def test_positive_value():
    assert my_function(5) == "Positive"  # Adapte selon ton code

def test_negative_value():
    assert my_function(-3) == "Negative"  # Adapte selon ton code

def test_zero_value():
    assert my_function(0) == "Zero"  # Adapte selon ton code
