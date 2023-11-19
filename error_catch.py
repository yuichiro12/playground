class FirstError(Exception):
    pass


class SecondError(Exception):
    pass


try:
    raise FirstError("First error")
except FirstError as e:
    try:
        print("First error caught")
        raise SecondError("Second error") from e
    except SecondError as e:
        print("Second error caught")
